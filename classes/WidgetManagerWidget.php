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
		"widget_manager_disable_widget_content_style" => NULL
	);
	public function __construct($guid = null) {
		parent::__construct($guid);
		if($this->getGUID()){
			$this->pre_load();
		}
	}
	protected function pre_load() {

		$this->settings_cache =  get_all_private_settings($this->getGUID());

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
}