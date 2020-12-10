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
	
		
	/**
	 * Returns whether or not the widget body should be lazy loaded
	 *
	 * @param \WidgetManagerWidget $widget      Widget to check the lazy loading logic for
	 * @param array                $layout_info Additional info about the layout the widget is shown in
	 *
	 * @return bool
	 */
	public function showLazyLoaded(\WidgetManagerWidget $widget, array $layout_info = []) {
		
		if (elgg_is_xhr()) {
			// no lazy loading body for ajax loaded widgets
			return false;
		}
		
		if (!((bool) elgg_get_plugin_setting('lazy_loading_enabled', 'widget_manager'))) {
			return false;
		}
		
		
		$detect_lazy_loading = function() use ($widget, $layout_info) {
			
			// configured in advanced widget config
			if ($widget->widget_manager_lazy_load_content) {
				return true;
			}
			
			// configured as always lazy loaded handler for context
			if ($this->getSetting($widget->handler, 'always_lazy_load', $widget->context)) {
				return true;
			}
						
			// under the fold
			$under_fold_limit = elgg_get_plugin_setting('lazy_loading_under_fold', 'widget_manager');
			if (!elgg_is_empty($under_fold_limit) && !empty($layout_info)) {
				// if invalid widgets are removed from the layout, the index could be messed up
				$column_widgets = array_values((array) elgg_extract($widget->column, $layout_info, []));
				
				foreach ($column_widgets as $column_index => $column_widget) {
					// if position in column is over the fold limit than it should be lazy loaded
					if ($column_index >= (int) $under_fold_limit) {
						return true;
					}
					
					if ($column_widget->guid === $widget->guid) {
						// do not look further
						break;
					}
				}
			}
			
			// mobile columns
			if ((bool) elgg_get_plugin_setting('lazy_loading_mobile_columns', 'widget_manager') && !empty($layout_info)) {
				$first_column_with_data = 3;
				while (empty($layout_info[$first_column_with_data])) {
					$first_column_with_data--;
					
					if ($first_column_with_data === 1) {
						break;
					}
				}
				
				if ($widget->column < $first_column_with_data) {
					$mobile_detect = new \Mobile_Detect();
					if ($mobile_detect->isMobile()) {
						return true;
					}
				}
			}
			
			return false;
		};
						
		return (bool) elgg_trigger_plugin_hook('lazy_load', 'widget_manager', ['entity' => $this, 'layout_info' => $layout_info], $detect_lazy_loading());
	}
}
