<?php 
/* init file for group_tidypics widget */

function widget_group_tidypics_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->photos_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_tidypics");
	}
}

function widget_group_tidypics_init(){
	if(elgg_is_active_plugin("tidypics")){
		elgg_register_widget_type("group_tidypics", elgg_echo("widgets:group_tidypics:title"), elgg_echo("widgets:group_tidypics:description"), "groups");
		widget_manager_add_widget_title_link("group_tidypics", "[BASEURL]photos/owned/[USERNAME]");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_tidypics_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_tidypics_pagesetup");