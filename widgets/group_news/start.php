<?php 
/* init file for group_news widget */

function widget_group_news_init(){
	
	if(elgg_is_active_plugin("groups") && elgg_is_active_plugin("blog")){
		elgg_register_widget_type('group_news',elgg_echo('widgets:group_news:title'),elgg_echo('widgets:group_news:description'), "profile,index,dashboard");
		elgg_extend_view("css", "widgets/group_news/css");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_news_init");