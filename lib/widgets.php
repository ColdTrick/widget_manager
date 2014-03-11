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

	// rss widget
	// load SimplePie autoloader
	require_once(elgg_get_plugins_path() . "widget_manager/widgets/rss/vendors/simplepie/autoloader.php");
	
	elgg_register_widget_type("rss", elgg_echo("widgets:rss:title"), elgg_echo("widgets:rss:description"), array("profile", "dashboard", "index", "groups"), true);
	
	// extend CSS
	elgg_extend_view("css/elgg", "widgets/rss/css");
	
	// make cache directory
	if (!is_dir(elgg_get_data_path() . "/widgets/")) {
		mkdir(elgg_get_data_path() . "/widgets/");
	}
	
	if (!is_dir(elgg_get_data_path() . "/widgets/rss/")) {
		mkdir(elgg_get_data_path() . "/widgets/rss/");
	}
	
	// set cache settings
	define("WIDGETS_RSS_CACHE_LOCATION", elgg_get_data_path() . "widgets/rss/");
	define("WIDGETS_RSS_CACHE_DURATION", 600);

	// register cron for cleanup
	elgg_register_plugin_hook_handler("cron", "daily", "widget_rss_cron_handler");

	// image slider
	elgg_extend_view("css/elgg", "widgets/image_slider/css");
	elgg_register_widget_type("image_slider", elgg_echo("widget_manager:widgets:image_slider:name"), elgg_echo("widget_manager:widgets:image_slider:description"), array("index", "groups"), true);
	
}

/**
 * Removes cached rss feeds
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $params
 * @param unknown_type $return_value
 */
function widget_rss_cron_handler($hook, $type, $params, $return_value) {
	if ($fh = opendir(WIDGETS_RSS_CACHE_LOCATION)) {
		while ($filename = readdir($fh)) {
			if (is_file(WIDGETS_RSS_CACHE_LOCATION . $filename)) {
				if (filemtime(WIDGETS_RSS_CACHE_LOCATION . $filename) < (time() - (24 * 60 * 60))) {
					unlink(WIDGETS_RSS_CACHE_LOCATION . $filename);
				}
			}
		}
	}
}