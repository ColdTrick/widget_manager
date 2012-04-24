<?php 
/* init file for group_files widget */

function widget_group_files_init(){	
	if(elgg_is_active_plugin("file")){
		
		elgg_register_widget_type("group_files", elgg_echo("file:group"), elgg_echo("widgets:group_files:description"), "groups");
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_group_files_url");
	}
}

function widget_group_files_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();

	if(($page_owner instanceof ElggGroup) && ($page_owner->files_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_files");
	}
}


function widget_group_files_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "group_files"){
		if(($page_owner = elgg_get_page_owner_entity()) && ($page_owner instanceof ElggGroup)){
			$result = "/file/group/" . $page_owner->getGUID() . "/all";
		}
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_files_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_files_pagesetup");