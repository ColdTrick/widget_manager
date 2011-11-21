<?php 
/* init file for entity_statistics widget */

function widget_entity_statistics_init(){
	add_widget_type("entity_statistics", elgg_echo("widgets:entity_statistics:title"), elgg_echo("widgets:entity_statistics:description"), "index");	
}

register_elgg_event_handler("widgets_init", "widget_manager", "widget_entity_statistics_init");