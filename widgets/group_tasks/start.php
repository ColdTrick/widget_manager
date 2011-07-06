<?php 
/* init file for group_tasks widget */

function widget_group_tasks_pagesetup(){
	$page_owner = page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->tasks_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_tasks");
	}
}

function widget_group_tasks_init(){
	
	if(is_plugin_enabled("tasks")){
		add_widget_type("group_tasks", elgg_echo("tasks:group"), "", "groups");
		add_widget_title_link("group_tasks", "[BASEURL]pg/tasks/[USERNAME]/items");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_tasks_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_tasks_pagesetup");