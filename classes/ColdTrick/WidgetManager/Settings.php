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
		
		return implode(',', $hook->getValue());
	}
}
