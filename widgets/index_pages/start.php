<?php 
/* init file for index_pages widget */

function widget_index_pages_init(){
	if(elgg_is_active_plugin("pages")){
		elgg_register_widget_type("index_pages", elgg_echo("pages"), elgg_echo("widget_manager:widgets:index_pages:description"), "index", true);
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_index_pages_url");
	}
}

function widget_index_pages_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "index_pages"){
		if(elgg_is_active_plugin("members")){
			$result = "/pages/all";
		}
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_pages_init");