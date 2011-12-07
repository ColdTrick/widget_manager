<?php 
/* init file for group_videolist widget */

function widget_group_videolist_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->videolist_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_videolist");
	}
}

function widget_group_videolist_init(){
	
	if(elgg_is_active_plugin("videolist")){
		elgg_register_widget_type("group_videolist", elgg_echo("widgets:group_videolist:title"), elgg_echo("widgets:group_videolist:description"), "groups");
		widget_manager_add_widget_title_link("group_videolist", "[BASEURL]videolist/owned/[USERNAME]");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_videolist_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_videolist_pagesetup");