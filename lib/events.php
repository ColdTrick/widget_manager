<?php

	/**
	* Hook for new users
	*
	* @param $event
	* @param $object_type
	* @param $object
	* @return unknown_type
	*/
	function widget_manager_new_user($event, $object_type, $object){
		widget_manager_create_widgets($object);
	}
	
	function widget_manager_create_group_event_handler($event, $object_type, $object) {
		if($object instanceof ElggGroup){
			if(get_plugin_setting("group_option_default_enabled", "widget_manager") == "yes"){
				$object->widget_manager_enable = "yes";
			}
		}
	}
	