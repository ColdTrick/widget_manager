<?php 
/* init file for tagcloud widget */

function widget_tagcloud_init(){
	add_widget_type("tagcloud", elgg_echo("widgets:tagcloud:title"), elgg_echo("widgets:tagcloud:description"), "index,groups,profile,dashboard", false);
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_tagcloud_init");