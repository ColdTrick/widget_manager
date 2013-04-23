<?php 
/* init file for user_search widget */

function widget_user_search_init(){
	elgg_register_widget_type("user_search", elgg_echo("widgets:user_search:title"), elgg_echo("widgets:user_search:description"), "admin");
// 	elgg_extend_view("css/elgg", "widgets/twitter_search/css");
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_user_search_init");
