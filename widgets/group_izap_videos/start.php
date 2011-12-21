<?php 
/* init file for group_izap_videos widget */

function widget_group_izap_videos_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->izap_videos_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_izap_videos");
	}
}

function widget_group_izap_videos_init(){
	if(elgg_is_active_plugin("izap_videos")){
		elgg_register_widget_type("group_izap_videos", elgg_echo("widgets:group_izap_videos:title"), elgg_echo("widgets:group_izap_videos:description"), "groups");
		widget_manager_add_widget_title_link("group_izap_videos", "[BASEURL]videos/list/[USERNAME]");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_izap_videos_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_izap_videos_pagesetup");