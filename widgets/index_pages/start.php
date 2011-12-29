<?php 
/* init file for index_pages widget */

function widget_index_pages_init(){
	if(elgg_is_active_plugin("pages")){
		elgg_register_widget_type("index_pages", elgg_echo("pages"), elgg_echo("widget_manager:widgets:index_pages:description"), "index", true);
		widget_manager_add_widget_title_link("index_pages", "[BASEURL]pages/all/");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_pages_init");