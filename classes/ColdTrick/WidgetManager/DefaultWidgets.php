<?php

namespace ColdTrick\WidgetManager;

/**
 * DefaultWidgets
 */
class DefaultWidgets {
	/**
	 * Allow for group default widgets
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return string
	 */
	public static function addGroupsContextToDefaultWidgets($hook_name, $entity_type, $return_value, $params) {
		if (!is_array($return_value)) {
			$return_value = [];
		}
	
		$return_value[] = [
			'name' => elgg_echo('groups'),
			'widget_context' => 'groups',
			'widget_columns' => 2,
			'event' => 'create',
			'entity_type' => 'group',
			'entity_subtype' => NULL,
		];
	
		return $return_value;
	}
}