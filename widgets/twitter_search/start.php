<?php 
/* init file for twitter_search widget */

function widget_twitter_search_init(){
	add_widget_type("twitter_search", elgg_echo("widgets:twitter_search:name"), elgg_echo("widgets:twitter_search:description"), "all", true);
	elgg_extend_view("metatags", "widgets/twitter_search/metatags");
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_twitter_search_init");
