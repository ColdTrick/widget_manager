<?php 
/* init file for index_members widget */

function widget_index_members_init(){
	add_widget_type("index_members", elgg_echo("widget_manager:widgets:index_members:name"), elgg_echo("widget_manager:widgets:index_members:description"), "index", true);
		
	if(widget_manager_is_page_handler_registered("members")){
		add_widget_title_link("index_members", "[BASEURL]pg/members");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_members_init");
