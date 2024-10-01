<?php

namespace ColdTrick\WidgetManager;

use Elgg\WidgetDefinition;

/**
 * Widgets
 */
class Widgets {
	
	/**
	 * Updates widget access for private widgets in group or site
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public static function fixPrivateAccess(\Elgg\Event $event): void {
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
	 * @param \Elgg\Event $event 'handlers', 'widgets'
	 *
	 * @return array
	 */
	public static function applyWidgetsConfig(\Elgg\Event $event): array {
		$return_value = $event->getValue();
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
	 * @param \Elgg\Event $event 'handlers', 'widgets'
	 *
	 * @return array
	 */
	public static function addManageWidgetsContext(\Elgg\Event $event): array {
		$return_value = $event->getValue();
		foreach ($return_value as $id => $widget_definition) {
			$widget_definition->context[] = 'manage_widgets';
			$return_value[$id] = $widget_definition;
		}
		
		return $return_value;
	}
	
	/**
	 * Returns widget content from cache
	 *
	 * @param \Elgg\Event $event 'view_vars', 'object/widget/body'
	 *
	 * @return null|array
	 */
	public static function getContentFromCache(\Elgg\Event $event): ?array {
		$widget = elgg_extract('entity', $event->getValue());
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return null;
		}
		
		$current_language = elgg_get_current_language();
		$cached_data = elgg_load_system_cache("widget_cache_{$widget->guid}_{$current_language}");
		if (empty($cached_data)) {
			return null;
		}
		
		$result = $event->getValue();
		$result[\Elgg\ViewsService::OUTPUT_KEY] = $cached_data;
		
		return $result;
	}
	
	/**
	 * Prevent widget controls
	 *
	 * @param \Elgg\Event $event 'view_vars', 'object/widget/elements/controls'
	 *
	 * @return null|array
	 */
	public static function preventControls(\Elgg\Event $event): ?array {
		$widget = elgg_extract('widget', $event->getValue());
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
		
		if ($widget->widget_manager_hide_header !== 'yes') {
			return null;
		}
		
		if ($widget->canEdit()) {
			return null;
		}
		
		$result = $event->getValue();
		$result[\Elgg\ViewsService::OUTPUT_KEY] = '';
		
		return $result;
	}

	/**
	 * Returns widget content from cache
	 *
	 * @param \Elgg\Event $event 'view', 'object/widget/body'
	 *
	 * @return void
	 */
	public static function saveContentInCache(\Elgg\Event $event): void {
		$widget = elgg_extract('entity', elgg_extract('vars', $event->getParams()));
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return;
		}
		
		$current_language = elgg_get_current_language();
		elgg_save_system_cache("widget_cache_{$widget->guid}_{$current_language}", $event->getValue());
	}
	
	/**
	 * Unsets the cached data for cacheable widgets
	 *
	 * @param \Elgg\Event $event 'update:after', 'object'
	 *
	 * @return void
	 */
	public static function clearWidgetCacheOnUpdate(\Elgg\Event $event): void {
		$widget = $event->getObject();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if (!self::isCacheableWidget($widget)) {
			return;
		}
	
		$languages = elgg()->translator->getAllowedLanguages();
		foreach ($languages as $language) {
			elgg_delete_system_cache("widget_cache_{$widget->guid}_{$language}");
		}
	}
	
	/**
	 * Checks if the provide widget is registered as a cacheable widget
	 *
	 * @param \ElggWidget $widget widget to check
	 *
	 * @return bool
	 */
	protected static function isCacheableWidget(\ElggWidget $widget): bool {
		static $cacheable_handlers;
		if (!isset($cacheable_handlers)) {
			$cacheable_handlers = elgg_trigger_event_results('cacheable_handlers', 'widget_manager', [], []);
		}
	
		return in_array($widget->handler, $cacheable_handlers);
	}
		
	/**
	 * Fallback widget title urls for non widget manager widgets
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object'
	 *
	 * @return null|string
	 */
	public static function getWidgetURL(\Elgg\Event $event): ?string {
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
	
		// custom urls always trump existing values
		return $widget->widget_manager_custom_url ?: null;
	}
		
	/**
	 * Register the discussions widget to groups context
	 *
	 * @param \Elgg\Event $event 'handlers', 'widgets'
	 *
	 * @return null|\Elgg\WidgetDefinition[]
	 */
	public static function addDiscussionsWidgetToGroup(\Elgg\Event $event): ?array {
		
		$context = $event->getParam('context');
		if ($context !== 'groups') {
			return null;
		}
		
		$container = $event->getParam('container');
		if (!$container instanceof \ElggGroup || !$container->isToolEnabled('forum')) {
			return null;
		}
		
		$return_value = $event->getValue();
		
		$return_value[] = WidgetDefinition::factory([
			'id' => 'discussions',
			'context' => ['groups'],
		]);
		
		return $return_value;
	}
			
	/**
	 * Add or remove widgets based on the group tool option
	 *
	 * @param \Elgg\Event $event 'group_tool_widgets', 'widget_manager'
	 *
	 * @return null|array
	 */
	public static function groupToolWidgets(\Elgg\Event $event): ?array {
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return null;
		}
		
		$return_value = $event->getValue();
		if (!is_array($return_value)) {
			return null;
		}
		
		// check different group tools for which we supply widgets
		if ($entity->isToolEnabled('forum')) {
			$return_value['enable'][] = 'discussions';
		} else {
			$return_value['disable'][] = 'discussions';
		}
		
		return $return_value;
	}
	
	/**
	 * Checks if a user can manage current widget layout
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'widget_layout'
	 *
	 * @return null|bool
	 */
	public static function layoutPermissionsCheck(\Elgg\Event $event): ?bool {
		$user = $event->getUserParam();
		if (!$user instanceof \ElggUser || $event->getValue()) {
			return null;
		}
		
		// check if widgetpage manager can manage
		$page_owner = $event->getParam('page_owner');
		if ($page_owner instanceof \WidgetPage) {
			if ($page_owner->canEdit()) {
				return true;
			}
			
			return null;
		}
		
		// check if it is an index manager
		if ($event->getParam('context') !== 'index') {
			return null;
		}
		
		$index_managers = explode(',', elgg_get_plugin_setting('index_managers', 'widget_manager', ''));
		return in_array($user->guid, $index_managers) ?: null;
	}
}
