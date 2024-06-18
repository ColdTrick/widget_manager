<?php

namespace ColdTrick\WidgetManager\Menus;

use Elgg\Menu\MenuItems;

/**
 * Entity menu callbacks
 */
class Entity {

	/**
	 * Adds an optional fix link to the menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity'
	 *
	 * @return null|MenuItems
	 */
	public static function addWidgetPageEntityMenuItems(\Elgg\Event $event): ?MenuItems {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \WidgetPage || !$entity->canEdit()) {
			return null;
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
}
