<?php

namespace ColdTrick\WidgetManager;

class Menus {
	
	/**
	 * Hook to register menu items on the admin pages
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return boolean
	 */
	public static function registerAdminPageMenu(\Elgg\Hook $hook) {
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
		
		$return_value = $hook->getValue();
		foreach ($return_value as $menu_item) {
			if ($menu_item->getName() == 'default_widgets') {
				// move defaultwidgets menu item
				$menu_item->setParentName('widgets');
			}
		}
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets',
			'text' => elgg_echo('admin:widgets'),
			'section' => 'configure',
		]);
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:manage',
			'href' => 'admin/widgets/manage',
			'text' => elgg_echo('admin:widgets:manage'),
			'parent_name' => 'widgets',
			'section' => 'configure',
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:cleanup',
			'href' => 'admin/widgets/cleanup',
			'text' => elgg_echo('admin:widgets:cleanup'),
			'parent_name' => 'widgets',
			'section' => 'configure',
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'widgets:pages',
			'href' => 'admin/widgets/pages',
			'text' => elgg_echo('admin:widgets:pages'),
			'parent_name' => 'widgets',
			'section' => 'configure',
		]);
		
		if (elgg_get_plugin_setting('custom_index', 'widget_manager') == '1|0') {
			// a special link to manage homepages that are only available if logged out
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'admin:widgets:manage:index',
				'href' => elgg_get_site_url() . '?override=true',
				'text' => elgg_echo('admin:widgets:manage:index'),
				'parent_name' => 'widgets',
				'section' => 'configure',
			]);
		}
		
		return $return_value;
	}

	/**
	 * Adds an optional fix link to the menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return array
	 */
	public static function addWidgetPageEntityMenuItems(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \WidgetPage || !$entity->canEdit()) {
			return;
		}
		
		$result = $hook->getValue();
			
		$result[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'icon' => 'edit',
			'href' => "ajax/form/widget_manager/widget_page?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
		]);
	
		return $result;
	}

	/**
	 * Adds a toggle to show/hide widget contents
	 *
	 * @param \Elgg\Hook $hook 'register', 'title:widgets'
	 *
	 * @return array
	 */
	public static function addWidgetsContentToggle(\Elgg\Hook $hook) {
		
		if (!elgg_get_plugin_setting('show_collapse_content', 'widget_manager')) {
			return;
		}
		
		if (!$hook->getParam('show_collapse_content', false)) {
			return;
		}
		
		$result = $hook->getValue();
			
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

	/**
	 * Optionally removes the edit and delete links from the menu
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'menu:widget'
	 *
	 * @return array
	 */
	public static function prepareWidgetEditDeleteMenuItems(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		$return_value = $hook->getValue();
	
		foreach ($return_value as $section_key => $section) {
			foreach ($section as $item_key => $item) {
				
				if ($item->getName() == 'settings') {
					$show_access = elgg_get_config('widget_show_access');
					$item->setHref('ajax/view/widget_manager/widgets/settings?guid=' . $widget->guid . '&show_access=' . $show_access);

					$item->{"data-colorbox-opts"} = '{"width": 750, "max-height": "80%", "trapFocus": false, "fixed": true}';
					$link_classes = explode(' ', $item->getLinkClass());
					if (($key = array_search('elgg-toggle', $link_classes)) !== false) {
					    unset($link_classes[$key]);
					}
					$link_classes[] = 'elgg-lightbox';
					$item->setLinkClass($link_classes);
				}
			}
		}
	
		return $return_value;
	}
}
