<?php 
/* init file for group_event_calendar widget */

function widget_group_event_calendar_pagesetup(){
	$page_owner = page_owner_entity();
	
 
	if(($page_owner instanceof ElggGroup) && ($page_owner->event_calender_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_event_calendar");
	}
}

function widget_group_event_calendar_init(){
	
	if(is_plugin_enabled("event_calendar") ){
		add_widget_type("group_event_calendar", elgg_echo("widgets:group_event_calendar:title"), elgg_echo("widgets:group_event_calendar:description"), "groups");		
		add_widget_title_link("group_event_calendar", "[BASEURL]pg/event_calendar/group/[GUID]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_event_calendar_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_event_calendar_pagesetup");