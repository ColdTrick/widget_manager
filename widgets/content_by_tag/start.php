<?php 
/* init file for widget */

function widget_content_by_tag_init(){
	if(is_plugin_enabled("blog") || is_plugin_enabled("file") || is_plugin_enabled("pages")){
		add_widget_type("content_by_tag", elgg_echo("widgets:content_by_tag:name"), elgg_echo("widgets:content_by_tag:description"), "all", true);
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_content_by_tag_init");