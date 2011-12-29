<?php 
/* init file for index_bookmarks widget */

function widget_index_bookmarks_init(){
	if(elgg_is_active_plugin("bookmarks")){
		elgg_register_widget_type("index_bookmarks", elgg_echo("bookmarks"), elgg_echo("widget_manager:widgets:index_bookmarks:description"), "index", true);
		widget_manager_add_widget_title_link("index_bookmarks", "[BASEURL]bookmarks/all/");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_index_bookmarks_init");