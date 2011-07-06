<?php 
/* init file for index_pages widget */

function widget_index_pages_init(){
	if(is_plugin_enabled("pages")){
		add_widget_type("index_pages", elgg_echo("widget_manager:widgets:index_pages:name"), elgg_echo("widget_manager:widgets:index_pages:description"), "index", true);
		add_widget_title_link("index_pages", "[BASEURL]pg/pages/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_pages_init");