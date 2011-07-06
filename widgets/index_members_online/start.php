<?php 
/* init file for index_members_online widget */

function widget_index_members_online_init(){
	add_widget_type("index_members_online", elgg_echo("widget_manager:widgets:index_members_online:name"), elgg_echo("widget_manager:widgets:index_members_online:description"), "index", true);
		
	if(widget_manager_is_page_handler_registered("members")){
		add_widget_title_link("index_members_online", "[BASEURL]pg/members");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_members_online_init");