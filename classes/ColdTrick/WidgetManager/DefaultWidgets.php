<?php

namespace ColdTrick\WidgetManager;

/**
 * DefaultWidgets
 */
class DefaultWidgets {
	
	/**
	 * Registers JS when viewing defaultWidgets admin page
	 *
	 * @param string  $hook_name    name of the hook
	 * @param string  $entity_type  type of the hook
	 * @param unknown $return_value return value
	 * @param unknown $params       hook parameters
	 *
	 * @return void
	 */
	public static function defaultWidgetsViewVars($hook_name, $entity_type, $return_value, $params) {
		elgg_require_js('widget_manager/toggle_fix');
	}
}