<?php

namespace ColdTrick\WidgetManager;

use Elgg\Hook;
use Elgg\Groups\Tool;

class Groups {

	/**
	 * Allow for group default widgets
	 *
	 * @param \Elgg\Hook $hook 'get_list', 'default_widgets'
	 *
	 * @return string
	 */
	public static function addGroupsContextToDefaultWidgets(\Elgg\Hook $hook) {
		if (!elgg_is_active_plugin('groups')) {
			return;
		}
		
		$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($group_enable, ['yes', 'forced'])) {
			return;
		}
		
		$return_value = $hook->getValue();
		
		if (!is_array($return_value)) {
			$return_value = [];
		}
		
		$return_value[] = [
			'name' => elgg_echo('groups'),
			'widget_context' => 'groups',
			'widget_columns' => 2,
			'event' => 'create',
			'entity_type' => 'group',
			'entity_subtype' => null,
		];
		
		return $return_value;
	}
	
	/**
	 * Sets the widget manager tool option. This is needed because in some situation the tool option is not available.
	 *
	 * And add/remove tool enabled widgets
	 *
	 * @param \Elgg\Event $event 'update', 'group'
	 *
	 * @return void
	 */
	public static function updateGroupWidgets(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggGroup || !elgg_is_active_plugin('groups')) {
			return;
		}
	
		$plugin_settings = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($plugin_settings, ['yes', 'forced'])) {
			return;
		}
		
		if ($plugin_settings === 'forced') {
			// make widget management mandatory
			$object->widget_manager_enable = 'yes';
		} elseif ($object->widget_manager_enable !== 'yes') {
			// if optional but not enabled for this group
			return;
		}
	
		// add/remove tool enabled widgets
		$result = ['enable' => [], 'disable' => []];
		$params = ['entity' => $object];
		$result = (array) elgg_trigger_plugin_hook('group_tool_widgets', 'widget_manager', $params, $result);

		if (empty($result)) {
			return;
		}
		
		// push an extra context for others to know what's going on
		elgg_push_context('widget_manager_group_tool_widgets');

		$current_widgets = elgg_get_widgets($object->guid, 'groups');

		// disable widgets
		$disable_widget_handlers = (array) elgg_extract('disable', $result, []);
		if (!empty($disable_widget_handlers) && !empty($current_widgets)) {
			foreach ($current_widgets as $column => $widgets) {
				if (!is_array($widgets) || empty($widgets)) {
					continue;
				}
				
				foreach ($widgets as $order => $widget) {
					// check if a widget should be removed
					if (!in_array($widget->handler, $disable_widget_handlers)) {
						continue;
					}

					// yes, so remove the widget
					$widget->delete();

					unset($current_widgets[$column][$order]);
				}
			}
		}

		// enable widgets
		$enable_widget_handlers = (array) elgg_extract('enable', $result, []);
		if (empty($enable_widget_handlers)) {
			// remove additional context
			elgg_pop_context();
			
			return;
		}
		
		$column_counts = [];
		$max_columns = elgg_trigger_plugin_hook('groups:column_count', 'widget_manager', [], elgg_get_plugin_setting('group_column_count', 'widget_manager'));
		for ($i = 1; $i <= $max_columns; $i++) {
			$column_counts[$i] = 0;
		}
		
		// ignore access restrictions
		// because if a group is created with a visibility of only group members
		// the group owner is not yet added to the acl and thus can't edit the newly created widgets
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object, $current_widgets, $enable_widget_handlers, $column_counts) {
			if (!empty($current_widgets) && is_array($current_widgets)) {
				foreach ($current_widgets as $column => $widgets) {
					// count for later balancing
					$column_counts[$column] = count($widgets);
						
					if (empty($widgets) || !is_array($widgets)) {
						continue;
					}
						
					foreach ($widgets as $order => $widget) {
						// check if a widget which sould be enabled isn't already enabled
						$enable_index = array_search($widget->handler, $enable_widget_handlers);
						if ($enable_index !== false) {
							// already enabled, do add duplicate
							unset($enable_widget_handlers[$enable_index]);
						}
					}
				}
			}
			
			$determine_target_column = function($column_counts) {
				
				$current_target = 1;
				$current_min = elgg_extract($current_target, $column_counts, 0);
				
				foreach ($column_counts as $column => $column_count) {
					if ($column_count < $current_min) {
						$current_target = $column;
						$current_min = $column_count;
					}
				}
				
				return $current_target;
			};
			
			// check blacklist
			$blacklist = $object->getPrivateSetting('widget_manager_widget_blacklist');
			if (!empty($blacklist)) {
				$blacklist = json_decode($blacklist, true);
				foreach ($blacklist as $handler) {
					$enable_index = array_search($handler, $enable_widget_handlers);
					if ($enable_index === false) {
						// blacklisted item wasn't going to be added
						continue;
					}
					
					// widget was removed manualy, don't add it automagicly
					unset($enable_widget_handlers[$enable_index]);
				}
			}
			
			// add new widgets
			if (!empty($enable_widget_handlers)) {
				$widget_access_id = $object->access_id;
				if ($widget_access_id === ACCESS_PUBLIC && elgg_get_config('walled_garden')) {
					$widget_access_id = ACCESS_LOGGED_IN;
				}
				
				foreach ($enable_widget_handlers as $handler) {
					$widget_guid = elgg_create_widget($object->guid, $handler, 'groups', $widget_access_id);
					if (empty($widget_guid)) {
						continue;
					}
						
					$widget = get_entity($widget_guid);
					
					$target_column = $determine_target_column($column_counts);

					// move to the end of the target column
					$widget->move($target_column, 9000);
					$column_counts[$target_column]++;
				}
			}
		});
		
		// remove additional context
		elgg_pop_context();
	}
	
	/**
	 * Bypasses the widgets content on the group profile
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'groups/profile/widgets'
	 *
	 * @return []
	 */
	public static function getGroupWidgetsLayout(\Elgg\Hook $hook) {
		$vars = $hook->getValue();
		$group = elgg_extract('entity', $vars);
		
		if (!$group instanceof \ElggGroup) {
			return;
		}
		
		$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($group_enable, ['forced', 'yes'])) {
			return;
		}
		
		if ($group_enable === 'yes' && !$group->isToolEnabled('widget_manager')) {
			return;
		}
		
		// need context = groups to fix the issue with the new group_profile context
		elgg_push_context('groups');
		
		$num_columns = (int) elgg_extract('num_columns', $vars, elgg_get_plugin_setting('group_column_count', 'widget_manager'));
		
		$vars[\Elgg\ViewsService::OUTPUT_KEY] = elgg_view_layout('widgets', [
			'num_columns' => $num_columns,
			'class' => [
				"widgets-{$num_columns}-columns",
			],
		]);
		
		elgg_pop_context();
		
		return $vars;
	}
	
	/**
	 * Adds the group tool option
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return []
	 */
	public static function registerGroupWidgetsTool(\Elgg\Hook $hook) {
		$plugin = elgg_get_plugin_from_id('widget_manager');
		if ($plugin->getSetting('group_enable') !== 'yes') {
			return;
		}
		
		$result = $hook->getValue();
		
		if (($plugin->getSetting('group_option_admin_only') !== 'yes') || elgg_is_admin_logged_in()) {
			// add the tool option for group admins
			$result[] = new Tool('widget_manager', [
				'label' => elgg_echo('widget_manager:groups:enable_widget_manager'),
				'default_on' => $plugin->getSetting('group_option_default_enabled') === 'yes',
			]);
		}
		
		return $result;
	}

	/**
	 * Prepare for group widget blacklist update when adding a widget manualy
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public static function addGroupWidget(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggWidget || elgg_in_context('widget_manager_group_tool_widgets') || !elgg_is_active_plugin('groups')) {
			return;
		}
		
		$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($group_enable, ['yes', 'forced'])) {
			return;
		}
		
		$owner = $object->getOwnerEntity();
		if (!$owner instanceof \ElggGroup) {
			// not a group widget
			return;
		}
		
		$blacklist = $owner->getPrivateSetting('widget_manager_widget_blacklist');
		if (empty($blacklist)) {
			// no blacklisted widgets, so no cleanup needed
			return;
		}
		
		global $widget_manager_group_guids;
		if (!isset($widget_manager_group_guids)) {
			$widget_manager_group_guids = [];
			
			elgg_register_event_handler('shutdown', 'system', self::class . '::addGroupWidgetShutdown');
		}
		$widget_manager_group_guids[$owner->guid][] = $object->guid;
	}
	
	/**
	 * Update the group widget blacklist when adding a widget manualy
	 *
	 * @param \Elgg\Event $event 'shutdown', 'system'
	 *
	 * @return void
	 */
	public static function addGroupWidgetShutdown(\Elgg\Event $event) {
		global $widget_manager_group_guids;
		
		if (empty($widget_manager_group_guids)) {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($widget_manager_group_guids) {
		
			foreach ($widget_manager_group_guids as $owner_guid => $widget_guids) {
				if (empty($widget_guids)) {
					continue;
				}
				
				$owner = get_entity($owner_guid);
				if (!$owner instanceof  \ElggGroup) {
					continue;
				}
				
				$blacklist = $owner->getPrivateSetting('widget_manager_widget_blacklist');
				if (empty($blacklist)) {
					// no blacklisted widgets, so no cleanup needed
					continue;
				}
				$blacklist = json_decode($blacklist, true);
				
				foreach ($widget_guids as $guid) {
					$widget = get_entity($guid);
					if (!$widget instanceof \ElggWidget) {
						continue;
					}
					
					if (!in_array($widget->handler, $blacklist)) {
						// not blacklisted, so no cleanup needed
						continue;
					}
					
					$key = array_search($widget->handler, $blacklist);
					unset($blacklist[$key]);
				}
				
				if (empty($blacklist)) {
					$owner->removePrivateSetting('widget_manager_widget_blacklist');
				} else {
					$owner->setPrivateSetting('widget_manager_widget_blacklist', json_encode($blacklist));
				}
			}
		});
	}
	
	/**
	 * Update the group widget blacklist when removing a widget manualy
	 *
	 * @param \Elgg\Event $event 'delete', 'object'
	 *
	 * @return void
	 */
	public static function deleteGroupWidget(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggWidget || elgg_in_context('widget_manager_group_tool_widgets') || !elgg_is_active_plugin('groups')) {
			return;
		}
		
		$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($group_enable, ['yes', 'forced'])) {
			return;
		}
		
		$owner = $object->getOwnerEntity();
		if (!$owner instanceof \ElggGroup) {
			// not a group widget
			return;
		}
		
		$handlers = elgg_call(ELGG_IGNORE_ACCESS, function() use ($owner, $object) {
			// get all group widgets
			$group_widgets = elgg_get_widgets($owner->guid, 'groups');
			$handlers = [];
			if (!empty($group_widgets)) {
				foreach ($group_widgets as $column => $widgets) {
					if (empty($widgets)) {
						continue;
					}
					
					/* @var $widget \ElggWidget */
					foreach ($widgets as $widget) {
						if ($widget->guid === $object->guid) {
							// don't add yourself
							continue;
						}
						
						$handlers[] = $widget->handler;
					}
				}
				
				$handlers = array_unique($handlers);
			}
			return $handlers;
		});
		
		if (in_array($object->handler, $handlers)) {
			// not the last widget of it's type
			return;
		}
		
		$blacklist = $owner->getPrivateSetting('widget_manager_widget_blacklist');
		if (!empty($blacklist)) {
			// blacklisted widgets
			$blacklist = json_decode($blacklist, true);
		} else {
			$blacklist = [];
		}
		
		if (in_array($object->handler, $blacklist)) {
			// already on the blacklist
			return;
		}
		
		$blacklist[] = $object->handler;
		
		// store new blacklist
		$owner->setPrivateSetting('widget_manager_widget_blacklist', json_encode($blacklist));
	}
}
