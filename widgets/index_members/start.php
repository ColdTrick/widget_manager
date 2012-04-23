<?php 
/* init file for index_members widget */

function widget_index_members_init(){
	elgg_register_widget_type("index_members", elgg_echo("widget_manager:widgets:index_members:name"), elgg_echo("widget_manager:widgets:index_members:description"), "index", true);

	if(elgg_is_active_plugin("members")){
		widget_manager_add_widget_title_link("index_members", "[BASEURL]members");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_members_init");
