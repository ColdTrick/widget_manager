<?php 
/* init file for group_izap_videos widget */

function widget_group_izap_videos_pagesetup(){
	$page_owner = page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->izap_videos_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_izap_videos");
	}
}

function widget_group_izap_videos_init(){
	
	if(is_plugin_enabled("izap_videos")){
		add_widget_type("group_izap_videos", elgg_echo("widgets:group_izap_videos:title"), elgg_echo("widgets:group_izap_videos:description"), "groups");
		add_widget_title_link("group_izap_videos", "[BASEURL]pg/videos/list/[USERNAME]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_izap_videos_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_izap_videos_pagesetup");