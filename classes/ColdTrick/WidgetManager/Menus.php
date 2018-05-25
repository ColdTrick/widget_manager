<?php

namespace ColdTrick\WidgetManager;

class Menus {
	
	/**
	 * Hook to register menu items on the admin pages
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return boolean
	 */
	public static function registerAdminPageMenu($hook_name, $entity_type, $return_value, $params) {
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
		
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
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return array
	 */
	public static function addFixDefaultWidgetMenuItem($hook_name, $entity_type, $return_value, $params) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		if (!elgg_in_context('default_widgets')) {
			return;
		}
		
		$widget = elgg_extract('entity', $params);
		if (!in_array($widget->context, ['profile', 'dashboard'])) {
			return;
		}
		
		$class = ['widget-manager-fix'];
		if ($widget->fixed) {
			$class[] = 'elgg-state-active';
		}
			
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'fix',
			'text' => elgg_view_icon('thumb-tack'),
			'title' => elgg_echo('widget_manager:widgets:fix'),
			'href' => "#{$widget->guid}",
			'link_class' => $class,
			'deps' => 'widget_manager/toggle_fix',
		]);
	
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
		if (!$entity instanceof \WidgetPage) {
			return;
		}
		
		if (!$entity->canEdit()) {
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
	 * Adds collapse widget control
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:widget_toggle'
	 *
	 * @return array
	 */
	public static function addWidgetToggleControls(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \WidgetManagerWidget) {
			return;
		}
		
		if (!$entity->canCollapse()) {
			return;
		}
		
		$result = $hook->getValue();
		
		$collapsed = $entity->showCollapsed();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'collapse',
			'icon' => 'chevron-down',
			'text' => false,
			'href' => elgg_generate_action_url('widget_manager/widgets/toggle_collapse', [
				'guid' => $entity->guid,
				'collapsed' => true,
			]),
			'link_class' => 'elgg-widget-collapse-button',
			'item_class' => $collapsed ? 'hidden' : '',
			'data-toggle' => 'expand',
			'rel' => 'toggle',
			'data-toggle-selector' => "#elgg-widget-{$entity->guid} > .elgg-body",
		]);

		$result[] = \ElggMenuItem::factory([
			'name' => 'expand',
			'icon' => 'chevron-right',
			'text' => false,
			'href' => elgg_generate_action_url('widget_manager/widgets/toggle_collapse', [
				'guid' => $entity->guid,
				'collapsed' => false,
			]),
			'link_class' => 'elgg-widget-collapse-button',
			'item_class' => $collapsed ? '' : 'hidden',
			'data-toggle' => 'collapse',
			'rel' => 'toggle',
			'data-toggle-selector' => "#elgg-widget-{$entity->guid} > .elgg-body",
		]);
	
		return $result;
	}
	
	/**
	 * Optionally removes the edit and delete links from the menu
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return array
	 */
	public static function prepareWidgetEditDeleteMenuItems($hook_name, $entity_type, $return_value, $params) {
	
		$widget = elgg_extract('entity', $params);
		if ($widget->fixed && !elgg_in_context('default_widgets') && !elgg_is_admin_logged_in()) {
			foreach ($return_value as $section_key => $section) {
				foreach ($section as $item_key => $item) {
					if (in_array($item->getName(), ['delete', 'settings'])) {
						unset($return_value[$section_key][$item_key]);
					}
				}
			}
		}
	
		foreach ($return_value as $section_key => $section) {
			foreach ($section as $item_key => $item) {
				
				if ($item->getName() == 'settings') {
					$show_access = elgg_get_config('widget_show_access');
					$item->setHref('ajax/view/widget_manager/widgets/settings?guid=' . $widget->getGUID() . '&show_access=' . $show_access);
					unset($item->rel);
					$item->{"data-colorbox-opts"} = '{"width": 750, "max-height": "80%", "trapFocus": false, "fixed": true}';
					$item->addLinkClass('elgg-lightbox');
				}
			}
		}
	
		return $return_value;
	}
}