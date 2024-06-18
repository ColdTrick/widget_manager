<?php

namespace ColdTrick\WidgetManager\Menus;

use Elgg\Menu\MenuItems;

/**
 * Admin Header menu callbacks
 */
class AdminHeader {
	
	/**
	 * Event to register menu items on the admin pages
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return null|MenuItems
	 */
	public static function registerAdminHeaderMenu(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_admin_logged_in()) {
			return null;
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
}
