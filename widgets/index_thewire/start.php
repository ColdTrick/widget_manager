<?php 
/* init file for index_thewire widget */

function widget_index_thewire_init(){
	if(is_plugin_enabled("thewire")){
		add_widget_type("index_thewire", elgg_echo("widget_manager:widgets:index_thewire:name"), elgg_echo("widget_manager:widgets:index_thewire:description"), "index", true);
		add_widget_title_link("index_thewire", "[BASEURL]pg/thewire/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_thewire_init");