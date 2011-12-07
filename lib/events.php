<?php

	/*
	 * Sets the widget manager tool option. This is needed because in some situation the tooloption is not available.
	 */
	function widget_manager_create_group_event_handler($event, $object_type, $object) {
		if($object instanceof ElggGroup){
			if(elgg_get_plugin_setting("group_option_default_enabled", "widget_manager") == "yes"){
				$object->widget_manager_enable = "yes";
			}
		}
	}
	