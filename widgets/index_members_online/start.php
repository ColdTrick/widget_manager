<?php 
/* init file for index_members_online widget */

function widget_index_members_online_init(){
	elgg_register_widget_type("index_members_online", elgg_echo("widget_manager:widgets:index_members_online:name"), elgg_echo("widget_manager:widgets:index_members_online:description"), "index", true);
		
	if(elgg_is_active_plugin("members")){
		widget_manager_add_widget_title_link("index_members_online", "[BASEURL]members");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_members_online_init");