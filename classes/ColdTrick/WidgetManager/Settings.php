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
	 * @return null|string
	 */
	public static function implodeSettings(\Elgg\Event $event): ?string {
		if ($event->getParam('plugin_id') !== 'widget_manager') {
			return null;
		}
		
		if ($event->getParam('name') !== 'index_managers') {
			return null;
		}
		
		$current_value = $event->getValue();
		if (!is_array($current_value)) {
			return null;
		}
		
		return implode(',', $current_value);
	}
}
