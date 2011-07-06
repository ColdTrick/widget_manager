<?php 
/* init file for thewire_post widget */

function widget_thewire_post_init(){
	if(is_plugin_enabled("thewire")){
		add_widget_type("thewire_post", elgg_echo("widgets:thewire_post:title"), elgg_echo("widgets:thewire_post:description"), "index,dashboard", false);
		add_widget_title_link("thewire_post", "[BASEURL]pg/thewire/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_thewire_post_init");