<?php 
/* init file for index_groups widget */

function widget_index_groups_init(){
	if(elgg_is_active_plugin("groups")){
		elgg_register_widget_type("index_groups", elgg_echo("groups"), elgg_echo("widget_manager:widgets:index_groups:description"), "index", true);
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_index_groups_url");
	}
}

function widget_index_groups_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "index_groups"){
		$result = "/groups/all";
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_groups_init");