<?php 
/* init file for discussion widget */

function widget_discussion_init(){
	if(elgg_is_active_plugin("groups")){
		elgg_register_widget_type("discussion", elgg_echo("discussion:latest"), elgg_echo("widgets:discussion:description"), "index,dashboard",true);
		widget_manager_add_widget_title_link("discussion", "[BASEURL]discussion/all");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_discussion_init");