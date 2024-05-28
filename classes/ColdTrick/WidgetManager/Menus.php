<?php

namespace ColdTrick\WidgetManager;

/**
 * Menu callbacks
 */
class Menus {
	
	/**
	 * Event to register menu items on the admin pages
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return boolean
	 */
	public static function registerAdminHeaderMenu(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return_value = $event->getValue();
		foreach ($return_value as $menu_item) {
			if ($menu_item->getName() == 'default_widgets') {
				// move defaultwidgets menu item
				$menu_item->setParentName('widgets');
			}
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets',
			'text' => elgg_echo('admin:widgets'),
			'href' => false,
			'parent_name' => 'configure',
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:manage',
			'href' => 'admin/widgets/manage',
			'text' => elgg_echo('admin:widgets:manage'),
			'parent_name' => 'widgets',
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:cleanup',
			'href' => 'admin/widgets/cleanup',
			'text' => elgg_echo('admin:widgets:cleanup'),
			'parent_name' => 'widgets',
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:pages',
			'href' => 'admin/widgets/pages',
			'text' => elgg_echo('admin:widgets:pages'),
			'parent_name' => 'widgets',
		]);
		
		if (elgg_get_plugin_setting('custom_index', 'widget_manager') == '1|0') {
			// a special link to manage homepages that are only available if logged out
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'admin:widgets:manage:index',
				'href' => elgg_get_site_url() . '?override=true',
				'text' => elgg_echo('admin:widgets:manage:index'),
				'parent_name' => 'widgets',
			]);
		}
		
		return $return_value;
	}

	/**
	 * Adds an optional fix link to the menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity'
	 *
	 * @return array
	 */
	public static function addWidgetPageEntityMenuItems(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \WidgetPage || !$entity->canEdit()) {
			return;
		}
		
		$result = $event->getValue();
			
		$result[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'icon' => 'edit',
			'href' => "ajax/form/widget_manager/widget_page?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
			'data-colorbox-opts' => json_encode([
				'trapFocus' => false,
			]),
		]);
	
		return $result;
	}

	/**
	 * Adds a toggle to show/hide widget contents
	 *
	 * @param \Elgg\Event $event 'register', 'title:widgets'
	 *
	 * @return array
	 */
	public static function addWidgetsContentToggle(\Elgg\Event $event) {
		
		if (!elgg_get_plugin_setting('show_collapse_content', 'widget_manager')) {
			return;
		}
		
		if (!$event->getParam('show_collapse_content', false)) {
			return;
		}
		
		$result = $event->getValue();
			
		$result[] = \ElggMenuItem::factory([
			'name' => 'hide-widget-contents',
			'class' => 'elgg-more',
			'text' => elgg_echo('widget_manager:layout:content:hide'),
			'icon' => 'eye-slash',
			'href' => false,
			'priority' => 80,
		]);
			
		$result[] = \ElggMenuItem::factory([
			'name' => 'show-widget-contents',
			'class' => 'elgg-more',
			'item_class' => 'hidden',
			'text' => elgg_echo('widget_manager:layout:content:show'),
			'icon' => 'eye',
			'href' => false,
			'priority' => 81,
		]);
		
		return $result;
	}
}
