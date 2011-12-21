<?php 
/* init file for group_files widget */

function widget_group_files_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->files_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_files");
	}
}

function widget_group_files_init(){	
	if(elgg_is_active_plugin("file")){
		elgg_register_widget_type("group_files", elgg_echo("widgets:group_files:title"), elgg_echo("widgets:group_files:description"), "groups");
		widget_manager_add_widget_title_link("group_files", "[BASEURL]file/owner/[USERNAME]");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_files_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_files_pagesetup");