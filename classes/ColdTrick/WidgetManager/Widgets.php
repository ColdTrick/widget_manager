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
		$widget = elgg_extract('entity', $hook->getValue());
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return;
		}
		
		$cached_data = elgg_load_system_cache("widget_cache_{$widget->guid}");
		if (empty($cached_data)) {
			return;
		}
		
		$result = $hook->getValue();
		$result[\Elgg\ViewsService::OUTPUT_KEY] = $cached_data;
		
		return $result;
	}
	
	/**
	 * Prevent widget controls
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return []
	 */
	public static function preventControls(\Elgg\Hook $hook) {
		$widget = elgg_extract('widget', $hook->getValue());
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if ($widget->widget_manager_hide_header !== 'yes') {
			return;
		}
		
		if ($widget->canEdit()) {
			return;
		}
		
		$result = $hook->getValue();
		$result[\Elgg\ViewsService::OUTPUT_KEY] = '';
		
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
		$widget = elgg_extract('entity', elgg_extract('vars', $hook->getParams()));
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return;
		}
		
		elgg_save_system_cache("widget_cache_{$widget->guid}", $hook->getValue());
	}
	
	/**
	 * Unsets the cached data for cacheable widgets
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return bool
	 */
	public static function clearWidgetCacheOnSettingsSave(\Elgg\Hook $hook) {
		$widget = $hook->getParam('widget');
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return;
		}
	
		elgg_delete_system_cache("widget_cache_{$widget->guid}");
	}
	
	/**
	 * Checks if the provide widget is registered as a cacheable widget
	 *
	 * @param ElggWidget $widget widget to check
	 *
	 * @return bool
	 */
	protected static function isCacheableWidget(\ElggWidget $widget) {
		static $cacheable_handlers;
		if (!isset($cacheable_handlers)) {
			$cacheable_handlers = elgg_trigger_plugin_hook('cacheable_handlers', 'widget_manager', [], []);
		}
	
		return in_array($widget->handler, $cacheable_handlers);
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
	
	/**
	 * Checks if a user can manage current widget layout
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'widget_layout'
	 *
	 * @return boolean
	 */
	public static function layoutPermissionsCheck(\Elgg\Hook $hook) {
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$result = $hook->getValue();
		if ($result) {
			return;
		}
		
		$context = $hook->getParam('context');
		
		if ($context === 'index') {
			$index_managers = explode(',', elgg_get_plugin_setting('index_managers', 'widget_manager', ''));
			if (in_array($user->guid, $index_managers)) {
				return true;
			}
		}
		
		$page_owner = $hook->getParam('page_owner');
		if (!($page_owner instanceof \ElggGroup) && !($page_owner instanceof \WidgetPage)) {
			return;
		}
		
		return $page_owner->canEdit($user->guid);
	}
}