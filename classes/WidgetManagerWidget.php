<?php

use ColdTrick\WidgetManager\CollapsedState;

/**
 * Extends the feature of default ElggWidgets
 *
 * @package WidgetManager
 */
class WidgetManagerWidget extends ElggWidget {
	use CollapsedState;
	
	/**
	 * Converts json to arrays
	 *
	 * @param string $name name of the setting/metadata
	 *
	 * @return mixed
	 */
	public function __get($name) {

		$result = parent::__get($name);
		
		// check if it should be an array
		$decoded_result = json_decode($result, true);
		if (is_array($decoded_result)) {
			$result = $decoded_result;
		}
		return $result;
	}

	/**
	 * Updates setting cache when parent data is set
	 *
	 * @param string $name  setting/metadata being updated
	 * @param mixed  $value new value
	 *
	 * @return void
	 */
	public function __set($name, $value) {
		if (is_array($value)) {
			if (empty($value)) {
				$value = null;
			} else {
				$value = json_encode($value);
			}
		}
		
		parent::__set($name, $value);
	}
	
	/**
	 * Can someone edit this widget (normal users can not edit fixed widgets)
	 *
	 * @param int $user_guid optional user_guid to check
	 *
	 * @return boolean
	 */
	public function canEdit($user_guid = 0) {
		$result = parent::canEdit($user_guid);
		
		if ($result && ($this->fixed && !elgg_is_admin_logged_in())) {
			$result = false;
		}
		return $result;
	}
	
	/**
	 * need to take over from ElggWidget to allow saving arrays
	 *
	 * @param array $params new settings
	 *
	 * @return boolean
	 */
	public function saveSettings($params) {
		if (!$this->canEdit()) {
			return false;
		}
	
		// plugin hook handlers should return true to indicate the settings have
		// been saved so that default code does not run
		$hook_params = ['widget' => $this, 'params' => $params];
		
		if (elgg_trigger_plugin_hook('widget_settings', $this->handler, $hook_params, false) == true) {
			return true;
		}
	
		if (is_array($params) && count($params) > 0) {
			foreach ($params as $name => $value) {
				$this->$name = $value;
			}
			$this->save();
		}
	
		return true;
	}
	
	/**
	 * Checks if a widget can be collapsed
	 *
	 * @return boolean
	 */
	public function canCollapse() {
		if (!elgg_is_logged_in()) {
			return false;
		}
		
		$result = $this->widget_manager_collapse_disable !== 'yes';
		
		$result = elgg_trigger_plugin_hook('collapsable', "widgets:{$this->handler}", ['entity' => $this], $result);
		
		return $result;
	}
	
	/**
	 * Return a boolean if the widget should show collapsed
	 *
	 * @return bool
	 */
	public function showCollapsed() {
		
		if (!$this->canCollapse()) {
			return false;
		}
		
		$default = ($this->widget_manager_collapse_state === 'closed');
		if (!elgg_is_logged_in()) {
			return $default;
		}
		
		if ($this->checkCollapsedState('widget_state_collapsed')) {
			return true;
		}

		if ($this->checkCollapsedState('widget_state_open')) {
			return false;
		}
		
		return $default;
	}
		
	/**
	 * Returns the custom title or the regular displayname
	 * {@inheritDoc}
	 * @see ElggWidget::getDisplayName()
	 */
	public function getDisplayName() {
		return $this->widget_manager_custom_title ?: parent::getDisplayName();
	}
}
