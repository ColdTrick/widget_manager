<?php 
/* init file for index_bookmarks widget */

function widget_index_bookmarks_init(){
	if(is_plugin_enabled("bookmarks")){
		add_widget_type("index_bookmarks", elgg_echo("widget_manager:widgets:index_bookmarks:name"), elgg_echo("widget_manager:widgets:index_bookmarks:description"), "index", true);
		add_widget_title_link("index_bookmarks", "[BASEURL]pg/bookmarks/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_bookmarks_init");