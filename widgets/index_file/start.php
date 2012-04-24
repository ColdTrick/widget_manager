<?php 
/* init file for index_file widget */

function widget_index_file_init(){
	if(elgg_is_active_plugin("file")){
		elgg_register_widget_type("index_file", elgg_echo("file"), elgg_echo("widget_manager:widgets:index_file:description"), "index", true);
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_index_file_url");
	}
}

function widget_index_file_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "index_file"){
		$result = "/file/all";
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_file_init");
