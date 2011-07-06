<?php 
/* init file for index_file widget */

function widget_index_file_init(){
	if(is_plugin_enabled("file")){
		add_widget_type("index_file", elgg_echo("widget_manager:widgets:index_file:name"), elgg_echo("widget_manager:widgets:index_file:description"), "index", true);
		add_widget_title_link("index_file", "[BASEURL]pg/file/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_file_init");
