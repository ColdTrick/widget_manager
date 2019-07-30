<?php

namespace ColdTrick\WidgetManager;

/**
 * DefaultWidgets
 */
class DefaultWidgets {
	/**
	 * Allow for group default widgets
	 *
	 * @param \Elgg\Hook $hook 'get_list', 'default_widgets'
	 *
	 * @return string
	 */
	public static function addGroupsContextToDefaultWidgets(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();

		if (!is_array($return_value)) {
			$return_value = [];
		}
	
		$return_value[] = [
			'name' => elgg_echo('groups'),
			'widget_context' => 'groups',
			'widget_columns' => 2,
			'event' => 'create',
			'entity_type' => 'group',
			'entity_subtype' => null,
		];
	
		return $return_value;
	}
}