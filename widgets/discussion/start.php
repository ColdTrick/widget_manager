<?php 
/* init file for discussion widget */

function widget_discussion_init(){
	if(elgg_is_active_plugin("groups")){
		elgg_register_widget_type("discussion", elgg_echo("discussion:latest"), elgg_echo("widgets:discussion:description"), "index,dashboard",true);
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_discussion_url");
	}
}

function widget_discussion_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "discussion"){
		$result = "/discussion/all";
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_discussion_init");