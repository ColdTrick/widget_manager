<?php 
/* init file for widget */

function widget_messages_init(){
	if(elgg_is_active_plugin("messages")){
		// extend CSS
		elgg_extend_view("css", "widgets/messages/css");
		
		elgg_register_widget_type("messages", elgg_echo("widgets:messages:title"), elgg_echo("widgets:messages:description"), "dashboard,index", false);
		widget_manager_add_widget_title_link("messages", "[BASEURL]messages/inbox/[USERNAME]");
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_messages_init");