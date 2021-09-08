<?php

namespace ColdTrick\WidgetManager;

class Widgets {
	
	/**
	 * Updates widget access for private widgets in group or site
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public static function fixPrivateAccess(\Elgg\Event $event) {
		$object = $event->getObject();
		if (!$object instanceof \ElggWidget) {
			return;
		}

		if ((int) $object->access_id !== ACCESS_PRIVATE) {
			return;
		}
		
		// Updates access for privately created widgets in a group or on site
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$owner = $object->getOwnerEntity();
		
			if ($owner instanceof \ElggGroup) {
				$object->access_id = $owner->getOwnedAccessCollection('group_acl')->id;
				$object->save();
			} elseif ($owner instanceof \ElggSite) {
				$object->access_id = ACCESS_PUBLIC;
				$object->save();
			}
		});
	}
			
	/**
	 * Applies the saved widgets config
	 *
	 * @param \Elgg\Hook $hook 'handlers', 'widgets'
	 *
	 * @return void
	 */
	public static function applyWidgetsConfig(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		foreach ($return_value as $id => $widget_definition) {
		
			if (!isset($widget_definition->originals)) {
				$widget_definition->originals = [
					'multiple' => $widget_definition->multiple,
					'context' => $widget_definition->context,
				];
			}
			
			$widget_config = WidgetsSettingsConfig::instance()->getAll($widget_definition->id);
			if (empty($widget_config)) {
				continue;
			}
			
			// fix multiple
			if (isset($widget_config['multiple'])) {
				$widget_definition->multiple = (bool) elgg_extract('multiple', $widget_config);
			}
			
			// fix contexts
			$contexts = elgg_extract('contexts', $widget_config);
			if (empty($contexts)) {
				continue;
			}
			
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
		
		return $return_value;
	}
			
	/**
	 * Adds manage_widgets context so the widgets always show up in admin/manage/widgets
	 *
	 * @param \Elgg\Hook $hook 'handlers', 'widgets'
	 *
	 * @return void
	 */
	public static function addManageWidgetsContext(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
		foreach ($return_value as $id => $widget_definition) {
			$widget_definition->context[] = 'manage_widgets';
			$return_value[$id] = $widget_definition;
		}
		
		return $return_value;
	}
	
	/**
	 * Returns widget content from cache
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'object/widget/body'
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
	 * @param \Elgg\Hook $hook 'view_vars', 'object/widget/elements/controls'
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
	 * @param \Elgg\Hook $hook 'view', 'object/widget/body'
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
	 * @param \Elgg\Hook $hook 'widget_settings', 'all'
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
	 * @param \ElggWidget $widget widget to check
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
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return string
	 */
	public static function getWidgetURL(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
	
		// custom urls always trump existing values
		if ($widget->widget_manager_custom_url) {
			return $widget->widget_manager_custom_url;
		}
		
		if (!empty($hook->getValue())) {
			// already got a link
			return;
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
				if ($owner instanceof \ElggGroup) {
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
		
		if ($hook->getValue()) {
			return;
		}
		
		// check if widgetpage manager can manage
		$page_owner = $hook->getParam('page_owner');
		if ($page_owner instanceof \WidgetPage) {
			if ($page_owner->canEdit()) {
				return true;
			}
			
			return;
		}
		
		// check if it is an index manager
		if ($hook->getParam('context') !== 'index') {
			return;
		}
		
		$index_managers = explode(',', elgg_get_plugin_setting('index_managers', 'widget_manager', ''));
		if (in_array($user->guid, $index_managers)) {
			return true;
		}
	}
}
