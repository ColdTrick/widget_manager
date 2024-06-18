<?php

namespace ColdTrick\WidgetManager\Menus;

use Elgg\Menu\MenuItems;

/**
 * Title entity menu callbacks
 */
class Title {
	
	/**
	 * Adds a toggle to show/hide widget contents
	 *
	 * @param \Elgg\Event $event 'register', 'menu:title:widgets'
	 *
	 * @return null|MenuItems
	 */
	public static function addWidgetsContentToggle(\Elgg\Event $event): ?MenuItems {
		
		if (!elgg_get_plugin_setting('show_collapse_content', 'widget_manager')) {
			return null;
		}
		
		if (!$event->getParam('show_collapse_content', false)) {
			return null;
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
