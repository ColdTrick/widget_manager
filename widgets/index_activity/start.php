<?php 
/* init file for index_activity widget */

function widget_index_activity_init(){
	add_widget_type("index_activity", elgg_echo("widget_manager:widgets:index_activity:name"), elgg_echo("widget_manager:widgets:index_activity:description"), "index", true);
			
	if(widget_manager_is_page_handler_registered("activity")){
		add_widget_title_link("index_activity", "[BASEURL]pg/activity");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_activity_init");
