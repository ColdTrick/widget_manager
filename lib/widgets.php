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
	elgg_register_widget_type('entity_statistics', elgg_echo('widgets:entity_statistics:title'), elgg_echo('widgets:entity_statistics:description'), ['index']);
	
	// free_html
	elgg_register_widget_type('free_html', elgg_echo('widgets:free_html:title'), elgg_echo('widgets:free_html:description'), ['profile', 'dashboard', 'index', 'groups'], true);

	// index_login
	elgg_register_widget_type('index_login', elgg_echo('login'), elgg_echo('widget_manager:widgets:index_login:description'), ['index']);
	
	// likes
	//elgg_register_widget_type("likes", elgg_echo("widgets:likes:title"), elgg_echo("widgets:likes:description"), "index,groups,profile,dashboard", true);
	
	// iframe
	elgg_register_widget_type('iframe', elgg_echo('widgets:iframe:title'), elgg_echo('widgets:iframe:description'), ['profile', 'dashboard', 'index', 'groups'], true);
	
	// user_search
	elgg_register_widget_type('user_search', elgg_echo('widgets:user_search:title'), elgg_echo('widgets:user_search:description'), ['admin']);

	// rss widget
	elgg_register_widget_type('rss', elgg_echo('widgets:rss:title'), elgg_echo('widgets:rss:description'), ['profile', 'dashboard', 'index', 'groups'], true);
	elgg_register_widget_type('rss_server', elgg_echo('widgets:rss_server:title'), elgg_echo('widgets:rss_server:description'), ['index'], true);
	elgg_register_plugin_hook_handler('widget_settings', 'rss_server', 'widget_manager_rss_server_widget_settings_hook_handler');
	
	// extend CSS
	elgg_extend_view('css/elgg', 'widgets/rss/content.css');
	elgg_extend_view('css/elgg', 'widgets/rss_server/content.css');
	
	// image slider
	elgg_extend_view('css/elgg', 'widgets/image_slider/content.css');
	elgg_register_widget_type('image_slider', elgg_echo('widget_manager:widgets:image_slider:name'), elgg_echo('widget_manager:widgets:image_slider:description'), ['index', 'groups'], true);
	
	// index activity
	elgg_register_widget_type('index_activity', elgg_echo('activity'), elgg_echo('widget_manager:widgets:index_activity:description'), ['index'], true);
	
	// twitter_search
	elgg_register_widget_type('twitter_search', elgg_echo('widgets:twitter_search:name'), elgg_echo('widgets:twitter_search:description'), ['profile', 'dashboard', 'index', 'groups'], true);
	elgg_register_plugin_hook_handler('widget_settings', 'twitter_search', 'widget_manager_widgets_twitter_search_settings_save_hook');
	
	// messages
	if (elgg_is_active_plugin('messages')) {
		elgg_register_widget_type('messages', elgg_echo('messages'), elgg_echo('widgets:messages:description'), ['dashboard', 'index'], false);
	}
	
	// index_members_online
	elgg_register_widget_type('index_members_online', elgg_echo('widget_manager:widgets:index_members_online:name'), elgg_echo('widget_manager:widgets:index_members_online:description'), ['index'], true);

	// index_members
	elgg_register_widget_type('index_members', elgg_echo('widget_manager:widgets:index_members:name'), elgg_echo('widget_manager:widgets:index_members:description'), ['index'], true);
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
	
	$widget = elgg_extract('entity', $params);
	if (!($widget instanceof ElggWidget)) {
		// not a widget
		return $result;
	}
	
	switch($widget->handler) {
		case 'index_activity':
			$result = '/activity';
			break;
		case 'messages':
			$user = elgg_get_logged_in_user_entity();
			if ($user) {
				$result = '/messages/inbox/' . $user->username;
			}
			break;
		case 'index_members_online':
		case 'index_members':
			if (elgg_is_active_plugin('members')) {
				$result = '/members';
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
	
	if ($type !== 'twitter_search') {
		return;
	}
	
	$widget = elgg_extract('widget', $params);
	if (empty($widget) || !elgg_instanceof($widget, 'object', 'widget')) {
		return;
	}
	
	// get embed code
	$embed_code = elgg_extract('embed_code', get_input('params', [], false)); // do not strip code

	if (empty($embed_code)) {
		return;
	}

	$pattern = '/data-widget-id=\"(\d+)\"/i';
	$matches = [];
	if (!preg_match($pattern, $embed_code, $matches)) {
		register_error(elgg_echo('widgets:twitter_search:embed_code:error'));
		return;
	}
	
	$widget->widget_id = $matches[1];
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
		'discussions' => 'discussion',
		'poll' => 'poll',
		'questions' => 'question',
		'static' => 'static',
		//'plugin_id' => 'subtype',
	];
	
	return elgg_trigger_plugin_hook('supported_content', 'widgets:content_by_tag', $result, $result);
}