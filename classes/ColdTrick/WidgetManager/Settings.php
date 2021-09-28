<?php

namespace ColdTrick\WidgetManager;

/**
 * Settings
 */
class Settings {
	
	/**
	 * Flattens the settings value for index managers
	 *
	 * @param \Elgg\Hook $hook 'setting', 'plugin'
	 *
	 * @return void
	 */
	public static function implodeSettings(\Elgg\Hook $hook) {
		if ($hook->getParam('plugin_id') !== 'widget_manager') {
			return;
		}
		
		if ($hook->getParam('name') !== 'index_managers') {
			return;
		}
		
		$current_value = $hook->getValue();
		if (!is_array($current_value)) {
			return;
		}
		
		return implode(',', $current_value);
	}
}
