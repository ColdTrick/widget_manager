<?php

namespace ColdTrick\WidgetManager;

use Elgg\WidgetDefinition;
use Elgg\Hook;
class Widgets {
	
	/**
	 * Updates widget access for private widgets in group or site
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function fixPrivateAccess($event, $object_type, $object) {
	
		if (!elgg_instanceof($object, 'object', 'widget', 'ElggWidget')) {
			return;
		}

		if ((int) $object->access_id !== ACCESS_PRIVATE) {
			return;
		}

		$owner = $object->getOwnerEntity();
		
		// Updates access for privately created widgets in a group or on site
		$old_ia = elgg_set_ignore_access();
		if ($owner instanceof \ElggGroup) {
			$object->access_id = $owner->group_acl;
			$object->save();
		} elseif ($owner instanceof \ElggSite) {
			$object->access_id = ACCESS_PUBLIC;
			$object->save();
		}
		elgg_set_ignore_access($old_ia);
	}
	
	/**
	 * Sets the fixed parent guid to default widgets to be used when cloning, so relationship can stay intact.
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function createFixedParentMetadata($event, $object_type, $object) {
		if (!($object instanceof \ElggWidget) || !in_array($event, ['create', 'update', 'delete'])) {
			return;
		}
	
		if (!stristr($_SERVER['HTTP_REFERER'], '/admin/appearance/default_widgets')) {
			return;
		}
	
		// on create set a parent guid
		if ($event == 'create') {
			$object->fixed_parent_guid = $object->guid;
		}
	
		// update time stamp
		$context = $object->context;
		if (empty($context)) {
			// only situation is on create probably, as context is metadata and saved after creation of the object, this is the fallback
			$context = get_input('context', false);
		}
	
		if ($context) {
			elgg_set_plugin_setting($context . '_fixed_ts', time(), 'widget_manager');
		}
	}
	
	/**
	 * Adds index widget handlers as allowed handlers to the extra context handlers
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function addExtraContextsWidgets($hook_name, $entity_type, $return_value, $params) {
		$context = elgg_extract('context', $params);
		if (!self::isExtraContext($context)) {
			return;
		}
		
		foreach ($return_value as $id => $widget_definition) {
			if (!in_array('index', $widget_definition->context)) {
				continue;
			}
			
			if (!in_array($context, $widget_definition->context)) {
				$widget_definition->context[] = $context;
			}
			
			$return_value[$id] = $widget_definition;
		}
		
		return $return_value;
	}
	
	protected static function isExtraContext($context) {
		$extra_contexts = elgg_get_plugin_setting('extra_contexts', 'widget_manager');
		if (empty($extra_contexts)) {
			return false;
		}
		
		$contexts = string_to_tag_array($extra_contexts);
		if (!is_array($contexts)) {
			return false;
		}
		
		return in_array($context, $contexts);
	}
	
	/**
	 * Changes widgets registered for the all context to be explictly registered for 'profile' and 'dashboard'
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function fixAllContext($hook_name, $entity_type, $return_value, $params) {
		foreach ($return_value as $id => $widget_definition) {
			if (!in_array('all', $widget_definition->context)) {
				continue;
			}
			
			if (!in_array('profile', $widget_definition->context)) {
				$widget_definition->context[] = 'profile';
			}
			
			if (!in_array('dashboard', $widget_definition->context)) {
				$widget_definition->context[] = 'dashboard';
			}
			
			$return_value[$id] = $widget_definition;
		}
		
		return $return_value;
	}
	
	/**
	 * Applies the saved widgets config
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param bool   $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function applyWidgetsConfig($hook_name, $entity_type, $return_value, $params) {
		foreach ($return_value as $id => $widget_definition) {
			$widget_config = widget_manager_get_widget_setting($widget_definition->id, 'all');
			if (empty($widget_config)) {
				continue;
			}
			
			if (!isset($widget_definition->originals)) {
				$widget_definition->originals = [
					'multiple' => $widget_definition->multiple,
					'context' => $widget_definition->context,
				];
			}
			
			// fix multiple
			if (isset($widget_config['multiple'])) {
				$widget_definition->multiple = (bool) elgg_extract('multiple', $widget_config);
			}
			
			// fix contexts
			$contexts = elgg_extract('contexts', $widget_config);
			if (!empty($contexts)) {
				foreach ($contexts as $context => $context_config) {
					if (!isset($context_config['enabled'])) {
						continue;
					}
					
					$enabled = elgg_extract('enabled', $context_config);
					$existing_key = array_search($context, $widget_definition->context);
					if ($existing_key !== false) {
						// already existing in default contexts
						if (!$enabled) {
							// remove if disabled in config
							unset($widget_definition->context[$existing_key]);
						}
					} elseif ($enabled) {
						// add if not existing
						$widget_definition->context[] = $context;
					}
				}
				$return_value[$id] = $widget_definition;
			}
		}
		
		return $return_value;
	}
	
	/**
	 * Returns widget content from cache
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return []
	 */
	public static function getContentFromCache(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		$cacheable = widget_manager_is_cacheable_widget($widget);
		
		if (!$cacheable) {
			return;
		}
		
		$cached_data = $widget->widget_manager_cached_data;
		if (empty($cached_data)) {
			return;
		}
		
		$result = $hook->getValue();
		$result[\Elgg\ViewsService::OUTPUT_KEY] = $cached_data;
		
		return $result;
	}

	/**
	 * Returns widget content from cache
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return []
	 */
	public static function saveContentInCache(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		$cacheable = widget_manager_is_cacheable_widget($widget);
		
		if (!$cacheable) {
			return;
		}
		
		$widget->widget_manager_cached_data = $hook->getValue();
	}
	
	/**
	 * Fallback widget title urls for non widget manager widgets
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return string
	 */
	public static function getWidgetURL(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!empty($hook->getValue())) {
			// already got a link
			return;
		}
		
		if ($widget->widget_manager_custom_url) {
			return $widget->widget_manager_custom_url;
		}
		
		if (elgg_in_context('default_widgets')) {
			return;
		}
		
		$owner = $widget->getOwnerEntity();
		switch ($widget->handler) {
			case 'friends':
				return elgg_generate_url('collection:friends:owner', [
					'username' => $owner->username,
				]);
			case 'messageboard':
				return elgg_generate_url('collection:annotation:messageboard:owner', [
					'username' => $owner->username,
				]);
			case 'river_widget':
				return elgg_generate_url('default:river');
			case 'bookmarks':
				if ($owner instanceof ElggGroup) {
					return elgg_generate_url('collection:object:bookmarks:group', [
						'guid' => $owner->guid,
					]);
				}
				return elgg_generate_url('collection:object:bookmarks:owner', [
					'username' => $owner->username,
				]);
		}
	}
	
	/**
	 * Updates fixed widgets on profile and dashboard
	 *
	 * @param \Elgg\Hook $hook hook
	 *
	 * @return void
	 */
	public static function checkFixedWidgets(\Elgg\Hook $hook) {
		if (elgg_in_context('default_widgets')) {
			return;
		}
		
		$context = elgg_get_context();
		if (!in_array($context, ['profile', 'dashboard'])) {
			// only check things if you are viewing a profile or dashboard page
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser) {
			return;
		}
		
		$fixed_ts = elgg_get_plugin_setting($context . '_fixed_ts', 'widget_manager');
		if (empty($fixed_ts)) {
			// there should always be a fixed ts, so fix it now. This situation only occurs after activating widget_manager the first time.
			$fixed_ts = time();
			elgg_set_plugin_setting($context . '_fixed_ts', $fixed_ts, 'widget_manager');
		}
		
		// get the ts of the profile/dashboard you are viewing
		$user_fixed_ts = elgg_get_plugin_user_setting($context . '_fixed_ts', $page_owner->guid, 'widget_manager');
		if ($user_fixed_ts < $fixed_ts) {
			widget_manager_update_fixed_widgets($context, $page_owner->guid);
		}
	}
}