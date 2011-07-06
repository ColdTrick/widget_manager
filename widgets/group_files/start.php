<?php 
/* init file for group_files widget */
	

function widget_group_files_pagesetup(){
	$page_owner = page_owner_entity();
	
	if(($page_owner instanceof ElggGroup) && ($page_owner->files_enable == "no")){
		// unset if not enabled for this plugin
		remove_widget_type("group_files");
	}
}

function widget_group_files_init(){
	
	if(is_plugin_enabled("file")){
		add_widget_type("group_files", elgg_echo("widgets:group_files:title"), elgg_echo("widgets:group_files:description"), "groups");
		add_widget_title_link("group_files", "[BASEURL]pg/file/owner/[USERNAME]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_group_files_init");
register_elgg_event_handler("widgets_pagesetup", "widget_manager", "widget_group_files_pagesetup");