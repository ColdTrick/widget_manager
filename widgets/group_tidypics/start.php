<?php 
/* init file for group_tidypics widget */

function widget_group_tidypics_pagesetup(){
	$page_owner = page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->photos_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_tidypics");
	}
}

function widget_group_tidypics_init(){
	
	if(is_plugin_enabled("tidypics")){
		add_widget_type("group_tidypics", elgg_echo("widgets:group_tidypics:title"), elgg_echo("widgets:group_tidypics:description"), "groups");
		add_widget_title_link("group_tidypics", "[BASEURL]pg/photos/owned/[USERNAME]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_tidypics_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_tidypics_pagesetup");