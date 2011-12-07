<?php 
/* init file for index_groups widget */

function widget_index_groups_init(){
	if(elgg_is_active_plugin("groups")){
		elgg_register_widget_type("index_groups", elgg_echo("widget_manager:widgets:index_groups:name"), elgg_echo("widget_manager:widgets:index_groups:description"), "index", true);
		widget_manager_add_widget_title_link("index_groups", "[BASEURL]groups/all/");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_groups_init");