<?php 
/* init file for index_file widget */

function widget_index_file_init(){
	if(elgg_is_active_plugin("file")){
		elgg_register_widget_type("index_file", elgg_echo("widget_manager:widgets:index_file:name"), elgg_echo("widget_manager:widgets:index_file:description"), "index", true);
		widget_manager_add_widget_title_link("index_file", "[BASEURL]file/all/");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_file_init");
