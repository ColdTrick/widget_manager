<?php

namespace ColdTrick\WidgetManager;

use Elgg\Di\ServiceFacade;

/**
 * Widgets Settings Config Service
 */
class WidgetsSettingsConfig {
	
	use ServiceFacade;
	
	/**
	 * Widgets configuration
	 */
	var $config = [];
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$widgets_config = elgg_get_plugin_setting('widgets_config', 'widget_manager');
		if ($widgets_config === null) {
			$this->config = [];
		} else {
			$this->config = json_decode($widgets_config, true);
		}
	}
	
	/**
	 * Returns all save settings for a widget
	 * 
	 * @param string $handler Widget to retrieve settings for
	 * @param string $context optional context to return settings for
	 * 
	 * @return []
	 */
	public function getAll(string $handler, string $context = null) {
		if (!isset($this->config[$handler]['contexts'])) {
			if (!isset($context)) {
				return [];
			}
			
			return ['contexts' => []];
		}
		
		if (empty($context)) {
			return $this->config[$handler];
		}
		
		return elgg_extract($context, $this->config[$handler]['contexts'], []);
	}
	
	/**
	 * Returns a setting value for a specific widget handler
	 * 
	 * @param string $handler Handler of the widget
	 * @param string $setting Setting to retrieve
	 * @param string $context Context for this setting (will default to current context)
	 * 
	 * @return boolean|null
	 */
	public function getSetting(string $handler, string $setting, string $context = '') {
		$context = $this->getContext($context);
		
		$setting_value = elgg_extract($setting, $this->getAll($handler, $context));
		if (isset($setting_value)) {
			return (bool) $setting_value;
		}
		
		if (!in_array($setting, ['can_add', 'hide'])) {
			// unsupported setting
			return null;
		}
		
		if ($setting === 'can_add') {
			// default widgets can be added
			return true;
		}
		
		// default for unexisting setting value
		return false;
	}
	
	/**
	 * Returns given context or detects current context
	 * 
	 * @param string $context
	 * 
	 * @return string
	 */
	protected function getContext(string $context = '') {
		if (empty($context)) {
			$context = elgg_get_context();
		}
		
		return $context;
	}
	
	/**
	 * Returns registered service name
	 * @return string
	 */
	public static function name() {
		return 'widgets.settings';
	}
}
