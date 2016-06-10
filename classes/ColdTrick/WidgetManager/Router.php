<?php

namespace ColdTrick\WidgetManager;

class Router {
	
	/**
	 * Hook to take over the index page
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return boolean
	 */
	public static function routeIndex($hook_name, $entity_type, $return_value, $params) {
		// identifier will be empty for the index page
		$identifier = elgg_extract('identifier', $return_value);
		if (!empty($identifier)) {
			return;
		}
	
		$setting = elgg_get_plugin_setting('custom_index', 'widget_manager');
		if (empty($setting)) {
			return;
		}
	
		list($non_loggedin, $loggedin) = explode('|', $setting);
	
		if ((!elgg_is_logged_in() && !empty($non_loggedin)) || (elgg_is_logged_in() && !empty($loggedin)) || (elgg_is_admin_logged_in() && (get_input('override') == true))) {
			elgg_register_page_handler('', 'widget_manager_index_page_handler');
		}
	}
	

	/**
	 * Routes the multidashboard pages
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return void
	 */
	public static function routeDashboard($hook_name, $entity_type, $return_value, $params) {
		$page = elgg_extract('segments', $return_value);
	
		$guid = (int) elgg_extract(0, $page);
	
		if (empty($guid)) {
			return;
		}
	
		if (get_entity($guid)) {
			set_input('multi_dashboard_guid', $guid);
		} else {
			register_error(elgg_echo('changebookmark'));
		}
	}
}