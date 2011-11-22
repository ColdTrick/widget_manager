<?php 
/* init file for widget */

function widget_messages_init(){
	if(is_plugin_enabled("messages")){
		// extend CSS
		elgg_extend_view("css", "widgets/messages/css");
		
		add_widget_type("messages", elgg_echo("widgets:messages:title"), elgg_echo("widgets:messages:description"), "dashboard,index", false);
		add_widget_title_link("messages", "[BASEURL]pg/messages/inbox/[USERNAME]");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_messages_init");