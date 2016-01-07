<?php
/**
 * This file handles all widgets initialization and other widget specific functionality
 */

/**
 * Inits the various widgets
 *
 * @return void
 */
function widget_manager_widgets_init() {
	
	// content_by_tag
	foreach (widget_manager_widgets_content_by_tag_get_supported_content() as $plugin => $subtype) {
		if (elgg_is_active_plugin($plugin)) {
			elgg_register_widget_type('content_by_tag', elgg_echo('widgets:content_by_tag:name'), elgg_echo('widgets:content_by_tag:description'), ['profile', 'dashboard', 'index', 'groups'], true);
			break;
		}
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

	// iframe
	elgg_register_widget_type("iframe", elgg_echo("widgets:iframe:title"), elgg_echo("widgets:iframe:description"), array("profile", "dashboard", "index", "groups"), true);
	
	// user_search
	elgg_register_widget_type("user_search", elgg_echo("widgets:user_search:title"), elgg_echo("widgets:user_search:description"), array("admin"));

	// rss widget
	elgg_register_widget_type("rss", elgg_echo("widgets:rss:title"), elgg_echo("widgets:rss:description"), array("profile", "dashboard", "index", "groups"), true);
	elgg_register_widget_type("rss_server", elgg_echo("widgets:rss_server:title"), elgg_echo("widgets:rss_server:description"), array("index"), true);
	elgg_register_plugin_hook_handler("widget_settings", "rss_server", "widget_manager_rss_server_widget_settings_hook_handler");
	
	// extend CSS
	elgg_extend_view("css/elgg", "widgets/rss/css");
	elgg_extend_view("css/elgg", "widgets/rss_server/css");
	
	// image slider
	elgg_extend_view("css/elgg", "widgets/image_slider/css");
	elgg_register_widget_type("image_slider", elgg_echo("widget_manager:widgets:image_slider:name"), elgg_echo("widget_manager:widgets:image_slider:description"), array("index", "groups"), true);
	
	// index activity
	elgg_register_widget_type("index_activity", elgg_echo("activity"), elgg_echo("widget_manager:widgets:index_activity:description"), array("index"), true);
	
	// bookmarks
	if (elgg_is_active_plugin("bookmarks")) {
		elgg_register_widget_type("index_bookmarks", elgg_echo("bookmarks"), elgg_echo("widget_manager:widgets:index_bookmarks:description"), array("index"), true);
	}
	
	// twitter_search
	elgg_register_widget_type("twitter_search", elgg_echo("widgets:twitter_search:name"), elgg_echo("widgets:twitter_search:description"), array("profile", "dashboard", "index", "groups"), true);
	elgg_register_plugin_hook_handler("widget_settings", "twitter_search", "widget_manager_widgets_twitter_search_settings_save_hook");
	
	// messages
	if (elgg_is_active_plugin("messages")) {
		elgg_register_widget_type("messages", elgg_echo("messages"), elgg_echo("widgets:messages:description"), array("dashboard", "index"), false);
	}
	
	// index_members_online
	elgg_register_widget_type("index_members_online", elgg_echo("widget_manager:widgets:index_members_online:name"), elgg_echo("widget_manager:widgets:index_members_online:description"), array("index"), true);

	// index_members
	elgg_register_widget_type("index_members", elgg_echo("widget_manager:widgets:index_members:name"), elgg_echo("widget_manager:widgets:index_members:description"), array("index"), true);
	
	// favorites
	elgg_register_widget_type("favorites", elgg_echo("widgets:favorites:title"), elgg_echo("widgets:favorites:description"), array("dashboard"));
	elgg_register_plugin_hook_handler('register', 'menu:extras', 'widget_manager_widgets_favorites_extras_register_hook');
	elgg_register_plugin_hook_handler('output:before', 'layout', 'widget_manager_widgets_favorites_layout_before_hook');
	elgg_register_action("favorite/toggle", elgg_get_plugins_path() . "widget_manager/actions/favorites/toggle.php");
}

/**
 * Returns urls for widget titles
 *
 * @param string $hook   name of the hook
 * @param string $type   type of the hook
 * @param string $return current return value
 * @param array  $params hook parameters
 *
 * @return string
 */
function widget_manager_widgets_url_hook_handler($hook, $type, $return, $params) {
	$result = $return;
	
	if ($result) {
		// someone else provided already a result
		return $result;
	}
	
	$widget = $params["entity"];
	if (!($widget instanceof ElggWidget)) {
		// not a widget
		return $result;
	}
	
	switch($widget->handler) {
		case "index_activity":
			$result = "/activity";
			break;
		case "index_bookmarks":
			$result = "/bookmarks/all";
			break;
		case "messages":
			$user = elgg_get_logged_in_user_entity();
			if ($user) {
				$result = "/messages/inbox/" . $user->username;
			}
			break;
		case "index_members_online":
		case "index_members":
			if (elgg_is_active_plugin("members")) {
				$result = "/members";
			}
			break;
	}
		
	return $result;
}

/**
 * Strips data-widget-id from submitted script code and saves that
 *
 * @param string $hook   name of the hook
 * @param string $type   type of the hook
 * @param string $return current return value
 * @param array  $params hook parameters
 *
 * @return void
 */
function widget_manager_widgets_twitter_search_settings_save_hook($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return;
	}
	
	if ($type !== "twitter_search") {
		return;
	}
	
	$widget = elgg_extract("widget", $params);
	if (empty($widget) || !elgg_instanceof($widget, 'object', 'widget')) {
		return;
	}
	
	// get embed code
	$embed_code = elgg_extract("embed_code", get_input("params", array(), false)); // do not strip code

	if (empty($embed_code)) {
		return;
	}

	$pattern = '/data-widget-id=\"(\d+)\"/i';
	$matches = array();
	if (!preg_match($pattern, $embed_code, $matches)) {
		register_error(elgg_echo("widgets:twitter_search:embed_code:error"));
		return;
	}
	
	$widget->widget_id = $matches[1];
}

/**
 * Function to register menu items for favorites widget
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return array
 */
function widget_manager_widgets_favorites_extras_register_hook($hook_name, $entity_type, $return_value, $params) {

	if (!widget_manager_widgets_favorites_has_widget()) {
		return;
	}

	global $FAVORITES_TITLE;
	
	if (empty($FAVORITES_TITLE)) {
		return;
	}
	
	$favorite = widget_manager_widgets_favorites_is_linked();

	
	$toggle_href = 'action/favorite/toggle?link=' . elgg_normalize_url(current_page_url()) . '&title=' . $FAVORITES_TITLE;
	
	$return_value[] = ElggMenuItem::factory([
		'name' => 'widget_favorites_add',
		'text' => elgg_view_icon('star-empty'),
		'href' => $toggle_href,
		'is_action' => true,
		'title' => elgg_echo('widgets:favorites:menu:add'),
		'item_class' => $favorite ? 'hidden' : '',
	]);
	
	$return_value[] = ElggMenuItem::factory([
		'name' => 'widget_favorites_remove',
		'text' => elgg_view_icon('star-alt'),
		'href' => $toggle_href,
		'is_action' => true,
		'title' => elgg_echo('widgets:favorites:menu:remove'),
		'item_class' => $favorite ? '' : 'hidden',
	]);
	
	return $return_value;
}

/**
 * Track the page title for use in sidebar menu
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return array
 */
function widget_manager_widgets_favorites_layout_before_hook($hook_name, $entity_type, $return_value, $params) {

	$title = elgg_extract('title', $return_value);
	if (empty($title)) {
		return;
	}
	
	global $FAVORITES_TITLE;
	$FAVORITES_TITLE = $title;
}

/**
 * Checks if a user has the favorites widget
 *
 * @param int $owner_guid GUID of the user that should own the widget, defaults to logged in user guid
 *
 * @return boolean
 */
function widget_manager_widgets_favorites_has_widget($owner_guid = 0) {
	if (empty($owner_guid) && elgg_is_logged_in()) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}
	
	if (empty($owner_guid)) {
		return false;
	}
	
	$options = [
		"type" => "object",
		"subtype" => "widget",
		"private_setting_name_value_pairs" => ["handler" => "favorites"],
		"count" => true,
		"owner_guid" => $owner_guid
	];

	return (bool) elgg_get_entities_from_private_settings($options);
}

/**
 * Returns the favorite object related to a given url
 *
 * @param string $url url to check, defaults to current page if empty
 *
 * @return false|ElggObject
 */
function widget_manager_widgets_favorites_is_linked($url = "") {
	if (empty($url)) {
		$url = current_page_url();
	}

	if (empty($url)) {
		return false;
	}
	
	$options = [
		"type" => "object",
		"subtype" => "widget_favorite",
		"joins" => ["JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid"],
		"wheres" => ["oe.description = '" . sanitise_string($url) . "'"],
		"limit" => 1
	];
	
	$entities = elgg_get_entities($options);
	if (empty($entities)) {
		return false;
	}
	
	return $entities[0];
}

/**
 * Returns the supported object subtypes to be used in the content_by_tag widget
 *
 * @return array
 */
function widget_manager_widgets_content_by_tag_get_supported_content() {
	$result = [
		'blog' => 'blog',
		'file' => 'file',
		'pages' => 'page',
		'bookmarks' => 'bookmarks',
		'thewire' => 'thewire',
		'videolist' => 'videolist_item',
		'event_manager' => 'event',
		'tasks' => 'task_top',
		'groups' => 'groupforumtopic',
		'poll' => 'poll',
		'questions' => 'question',
		'static' => 'static',
		//'plugin_id' => 'subtype',
	];
	
	return elgg_trigger_plugin_hook('supported_content', 'widgets:content_by_tag', $result, $result);
}