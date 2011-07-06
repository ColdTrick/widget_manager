<?php 
/* init file for index_groups widget */

function widget_index_groups_init(){
	if(is_plugin_enabled("groups")){
		add_widget_type("index_groups", elgg_echo("widget_manager:widgets:index_groups:name"), elgg_echo("widget_manager:widgets:index_groups:description"), "index", true);
		add_widget_title_link("index_groups", "[BASEURL]pg/groups/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_groups_init");