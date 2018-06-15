<?php

namespace ColdTrick\WidgetManager;

class Groups {
	
	/**
	 * Sets the widget manager tool option. This is needed because in some situation the tooloption is not available.
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function setGroupToolOption($event, $object_type, $object) {
	
		if (!elgg_instanceof($object, 'group')) {
			return;
		}
	
		if (elgg_get_plugin_setting('group_option_default_enabled', 'widget_manager') !== 'yes') {
			return;
		}
	
		$object->widget_manager_enable = 'yes';
	}

	/**
	 * Sets the widget manager tool option. This is needed because in some situation the tool option is not available.
	 *
	 * And add/remove tool enabled widgets
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function updateGroupWidgets($event, $object_type, $object) {
	
		if (!($object instanceof \ElggGroup)) {
			return;
		}
	
		$plugin_settings = elgg_get_plugin_setting('group_enable', 'widget_manager');
		// make widget management mandatory
		if ($plugin_settings == 'forced') {
			$object->widget_manager_enable = 'yes';
		}
	
		// add/remove tool enabled widgets
		if (($plugin_settings == 'forced') || (($plugin_settings == 'yes') && ($object->widget_manager_enable == 'yes'))) {
	
			$result = ['enable' => [], 'disable' => []];
			$params = ['entity' => $object];
			$result = elgg_trigger_plugin_hook('group_tool_widgets', 'widget_manager', $params, $result);
	
			if (empty($result) || !is_array($result)) {
				return;
			}
			
			// push an extra context for others to know what's going on
			elgg_push_context('widget_manager_group_tool_widgets');
	
			$current_widgets = elgg_get_widgets($object->getGUID(), 'groups');
	
			// disable widgets
			$disable_widget_handlers = elgg_extract('disable', $result);
			if (!empty($disable_widget_handlers) && is_array($disable_widget_handlers)) {
	
				if (!empty($current_widgets) && is_array($current_widgets)) {
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
			}
	
			// enable widgets
			$column_counts = [];
			$enable_widget_handlers = elgg_extract('enable', $result);
			if (!empty($enable_widget_handlers) || is_array($enable_widget_handlers)) {
					
				// ignore access restrictions
				// because if a group is created with a visibility of only group members
				// the group owner is not yet added to the acl and thus can't edit the newly created widgets
				$ia = elgg_set_ignore_access(true);
					
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
					foreach ($enable_widget_handlers as $handler) {
						$widget_guid = elgg_create_widget($object->getGUID(), $handler, 'groups', $object->access_id);
						if (empty($widget_guid)) {
							continue;
						}
							
						$widget = get_entity($widget_guid);
							
						if ($column_counts[1] <= $column_counts[2]) {
							// move to the end of the first column
							$widget->move(1, 9000);
	
							$column_counts[1]++;
						} else {
							// move to the end of the second
							$widget->move(2, 9000);
	
							$column_counts[2]++;
						}
					}
				}
					
				// restore access restrictions
				elgg_set_ignore_access($ia);
			}
			
			// remove additional context
			elgg_pop_context();
		}
	}
	
	/**
	 * Prepare for group widget blacklist update when adding a widget manualy
	 *
	 * @param string      $event  'create'
	 * @param string      $type   'object'
	 * @param \ElggWidget $object the new widget
	 *
	 * @return void
	 */
	public static function addGroupWidget($event, $type, $object) {
		
		if (!$object instanceof \ElggWidget || elgg_in_context('widget_manager_group_tool_widgets')) {
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
	 * @param string $event  'shutdown'
	 * @param string $type   'system'
	 * @param mixed  $object misc
	 *
	 * @return void
	 */
	public static function addGroupWidgetShutdown($event, $type, $object) {
		global $widget_manager_group_guids;
		
		if (empty($widget_manager_group_guids)) {
			return;
		}
		
		$ia = elgg_set_ignore_access(true);
		
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
		
		elgg_set_ignore_access($ia);
	}
	
	/**
	 * Update the group widget blacklist when removing a widget manualy
	 *
	 * @param string      $event  'delete'
	 * @param string      $type   'object'
	 * @param \ElggWidget $object the new widget
	 *
	 * @return void
	 */
	public static function deleteGroupWidget($event, $type, $object) {
		
		if (!$object instanceof \ElggWidget || elgg_in_context('widget_manager_group_tool_widgets')) {
			return;
		}
		
		$owner = $object->getOwnerEntity();
		if (!$owner instanceof \ElggGroup) {
			// not a group widget
			return;
		}
		
		// just in case
		$ia = elgg_set_ignore_access(true);
		
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
		
		// restore access
		elgg_set_ignore_access($ia);
		
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
