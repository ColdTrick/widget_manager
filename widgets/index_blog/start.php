<?php 
/* init file for index_blog widget */

function widget_index_blog_init(){
	if(is_plugin_enabled("blog")){
		add_widget_type("index_blog", elgg_echo("widget_manager:widgets:index_blog:name"), elgg_echo("widget_manager:widgets:index_blog:description"), "index", true);
		add_widget_title_link("index_blog", "[BASEURL]pg/blog/all/");
	}
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_index_blog_init");