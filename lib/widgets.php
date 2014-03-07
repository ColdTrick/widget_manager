<?php
/**
 * This file handles all widgets initialization and other widget specific functionality
 */

function widget_manager_widgets_init() {
	
	
	
	// content_by_tag
	if (elgg_is_active_plugin("blog") || elgg_is_active_plugin("file") || elgg_is_active_plugin("pages")) {
		elgg_register_widget_type("content_by_tag", elgg_echo("widgets:content_by_tag:name"), elgg_echo("widgets:content_by_tag:description"), array("profile", "dashboard", "index", "groups"), true);
	}
	
	// entity_statistics
	elgg_register_widget_type("entity_statistics", elgg_echo("widgets:entity_statistics:title"), elgg_echo("widgets:entity_statistics:description"), array("index"));
	
	// free_html
	elgg_register_widget_type("free_html", elgg_echo("widgets:free_html:title"), elgg_echo("widgets:free_html:description"), array("profile", "dashboard", "index", "groups"), true);

	// index_login
	elgg_register_widget_type("index_login", elgg_echo("login"), elgg_echo("widget_manager:widgets:index_login:description"), array("index"));
	
	// likes
	//elgg_register_widget_type("likes", elgg_echo("widgets:likes:title"), elgg_echo("widgets:likes:description"), "index,groups,profile,dashboard", true);
	
	// tagcloud
	elgg_register_widget_type("tagcloud", elgg_echo("tagcloud"), elgg_echo("widgets:tagcloud:description"), array("profile", "dashboard", "index", "groups"), false);
	
	// user_search
	elgg_register_widget_type("user_search", elgg_echo("widgets:user_search:title"), elgg_echo("widgets:user_search:description"), array("admin"));
}