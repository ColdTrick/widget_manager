<?php

namespace ColdTrick\WidgetManager;

/**
 * Settings
 */
class Settings {
	
	/**
	 * Flattens the settings value for index managers
	 *
	 * @param \Elgg\Event $event 'setting', 'plugin'
	 *
	 * @return void
	 */
	public static function implodeSettings(\Elgg\Event $event) {
		if ($event->getParam('plugin_id') !== 'widget_manager') {
			return;
		}
		
		if ($event->getParam('name') !== 'index_managers') {
			return;
		}
		
		$current_value = $event->getValue();
		if (!is_array($current_value)) {
			return;
		}
		
		return implode(',', $current_value);
	}
}
