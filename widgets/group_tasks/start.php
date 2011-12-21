<?php 
/* init file for group_tasks widget */

function widget_group_tasks_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->tasks_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_tasks");
	}
}

function widget_group_tasks_init(){
	if(elgg_is_active_plugin("tasks")){
		elgg_register_widget_type("group_tasks", elgg_echo("tasks:group"), "", "groups");
		widget_manager_add_widget_title_link("group_tasks", "[BASEURL]tasks/[USERNAME]/items");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_tasks_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_tasks_pagesetup");