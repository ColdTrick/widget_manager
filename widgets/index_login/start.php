<?php 
/* init file for index_login widget */

function widget_index_login_init(){
	add_widget_type("index_login", elgg_echo("widget_manager:widgets:index_login:name"), elgg_echo("widget_manager:widgets:index_login:description"), "index");	
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_login_init");