<?php 
/* init file for group_videolist widget */

function widget_group_videolist_pagesetup(){
	$page_owner = page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->videolist_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_videolist");
	}
}

function widget_group_videolist_init(){
	
	if(is_plugin_enabled("videolist")){
		add_widget_type("group_videolist", elgg_echo("widgets:group_videolist:title"), elgg_echo("widgets:group_videolist:description"), "groups");
		add_widget_title_link("group_videolist", "[BASEURL]pg/videolist/owned/[USERNAME]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_videolist_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_videolist_pagesetup");