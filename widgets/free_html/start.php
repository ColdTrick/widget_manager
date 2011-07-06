<?php 
/* init file for group_free_html widget */

function widget_free_html_init(){
	add_widget_type("free_html", elgg_echo("widgets:free_html:title"), elgg_echo("widgets:free_html:description"), "groups,index", true);
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_free_html_init");