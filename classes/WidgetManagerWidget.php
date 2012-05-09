<?php

class WidgetManagerWidget extends ElggWidget {
	protected $settings_cache;
	protected $settings_defaults = array(
 		"fixed" => NULL,
		"widget_manager_hide_header" => NULL,
		"widget_manager_show_toggle" => NULL,
		"widget_manager_show_edit" => NULL,
		"widget_manager_custom_title" => NULL,
		"widget_manager_custom_url" => NULL,
		"widget_manager_disable_widget_content_style" => NULL,
		"widget_manager_custom_class" => NULL
	);
	
	protected function load($guid) {
		// Load data from entity table if needed
		if (!parent::load($guid)) {
			return false;
		}
		
		// Only work with GUID from here
		if ($guid instanceof stdClass) {
			$guid = $guid->guid;
		}
		
		$query = "SELECT * from " . elgg_get_config("dbprefix"). "private_settings where entity_guid = {$guid}";
		$result = get_data($query);
		if ($result) {
			$this->settings_cache = array();
			foreach ($result as $r) {
				$this->settings_cache[$r->name] = $r->value;
			}
		}
		
		return true;
	}

	public function get($name) {
		if(is_array($this->settings_cache) && array_key_exists($name, $this->settings_cache)){
			return $this->settings_cache[$name];
		} elseif (array_key_exists($name, $this->settings_defaults)){
			return $this->settings_defaults[$name];
		}
		return parent::get($name);
	}

	public function set($name, $value){
		if(parent::set($name, $value)){
			$this->pre_load();
		}
	}
	
	public function getTitle(){
		if($custom_title = $this->widget_manager_custom_title){
			return $custom_title;
		} else {
			return parent::getTitle();
		}
	}
	
	public function getURL(){
		if($custom_url = $this->widget_manager_custom_url){
			return $custom_url;
		} else {
			return parent::getURL();
		}
	}
	
	public function canEdit($user_guid = 0){
		$result = parent::canEdit($user_guid);
		
		if($result && ($this->fixed && !elgg_is_admin_logged_in())){
			$result = false;
		}
		return $result;
	}
}