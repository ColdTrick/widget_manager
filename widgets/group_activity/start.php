<?php 
/* init file for group_activity widget */

function widget_group_activity_pagesetup(){

}

function widget_group_activity_init(){
	elgg_register_widget_type(
		"group_activity",
		elgg_echo("groups:activity"),
		elgg_echo("widgets:group_activity:description"),
		"groups",
		false);
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_activity_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_activity_pagesetup");