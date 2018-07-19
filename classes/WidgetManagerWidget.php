<?php

/**
 * Extends the feature of default ElggWidgets
 *
 * @package WidgetManager
 */
class WidgetManagerWidget extends ElggWidget {

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
		
		if (widget_manager_check_collapsed_state($this->guid, 'widget_state_collapsed')) {
			return true;
		}

		if (widget_manager_check_collapsed_state($this->guid, 'widget_state_open')) {
			return false;
		}
		
		return $default;
	}
	
	/**
	 * Store collapse preference for a user
	 *
	 * @param int $user_guid guid of the user. Defaults to logged in user
	 *
	 * @return boolean
	 */
	public function collapse($user_guid = 0) {
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		$user = get_entity($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}
			
		$user->addRelationship($this->guid, 'widget_state_collapsed');
		$user->removeRelationship($this->guid, 'widget_state_open');
		
		return true;
	}
	
	/**
	 * Store expand preference for a user
	 *
	 * @param int $user_guid guid of the user. Defaults to logged in user
	 *
	 * @return boolean
	 */
	public function expand($user_guid = 0) {
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		$user = get_entity($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}
			
		$user->addRelationship($this->guid, 'widget_state_open');
		$user->removeRelationship($this->guid, 'widget_state_collapsed');
		
		return true;
	}
	
	/**
	 * Returns the custom title or the regular displayname
	 * {@inheritDoc}
	 * @see ElggWidget::getDisplayName()
	 */
	public function getDisplayName() {
		$result = $this->widget_manager_custom_title;
		if ($result) {
			return $result;
		}
		
		return parent::getDisplayName();
	}
}
