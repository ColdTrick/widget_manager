<?php

define('ACCESS_LOGGED_OUT', -5);
define('MULTI_DASHBOARD_MAX_TABS', 7);

@include_once(dirname(__FILE__) . '/vendor/autoload.php');

require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/events.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/page_handlers.php');
require_once(dirname(__FILE__) . '/lib/widgets.php');

// register default Elgg events
elgg_register_event_handler('init', 'system', 'widget_manager_init');
elgg_register_event_handler('init', 'system', 'widget_manager_init_group');
elgg_register_event_handler('init', 'system', 'widget_manager_init_multi_dashboard');

/**
 * Used to perform initialization of the widget manager features.
 *
 * @return void
 */
function widget_manager_init() {
	
	$base_dir = dirname(__FILE__);
	
	// check valid WidgetManagerWidget class
	if (get_subtype_class('object', 'widget') == 'ElggWidget') {
		update_subtype('object', 'widget', 'WidgetManagerWidget');
	}
	
	// loads the widgets
	widget_manager_widgets_init();
	
	elgg_register_plugin_hook_handler('widget_settings', 'all', 'widget_manager_all_widget_settings_hook_handler');
	
	// register plugin hooks
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'widget_manager_write_access_hook', 999);
	elgg_register_plugin_hook_handler('access:collections:read', 'user', 'widget_manager_read_access_hook');
	elgg_register_plugin_hook_handler('action', 'widgets/save', 'widget_manager_widgets_save_hook');
	
	elgg_register_plugin_hook_handler('register', 'menu:widget', 'widget_manager_register_widget_menu');
	elgg_register_plugin_hook_handler('prepare', 'menu:widget', 'widget_manager_prepare_widget_menu');
	
	elgg_register_plugin_hook_handler('advanced_context', 'widget_manager', 'widget_manager_advanced_context');
	elgg_register_plugin_hook_handler('available_widgets_context', 'widget_manager', 'widget_manager_available_widgets_context');
	
	elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', 'widget_manager_widget_layout_permissions_check');
	
	// extend CSS
	elgg_extend_view('css/elgg', 'css/widget_manager/site.css');
	elgg_extend_view('css/elgg', 'css/widget_manager/global.css');
	
	elgg_extend_view('css/admin', 'css/widget_manager/admin.css');
	elgg_extend_view('css/admin', 'css/widget_manager/global.css');
	
	elgg_extend_view('js/elgg', 'js/widget_manager/site.js');
	
	elgg_register_plugin_hook_handler('format', 'friendly:time', 'widget_manager_friendly_time_hook');
	
	// register a widget title url handler
	// core widgets
	elgg_register_plugin_hook_handler('entity:url', 'object', 'widget_manager_widgets_url');
	// widget manager widgets
	elgg_register_plugin_hook_handler('entity:url', 'object', 'widget_manager_widgets_url_hook_handler');
	// dashboard
	elgg_register_plugin_hook_handler('entity:url', 'object', 'widget_manager_dashboard_url');

	// cacheable widget handlers
	elgg_register_plugin_hook_handler('cacheable_handlers', 'widget_manager', 'widget_manager_cacheable_handlers_hook_handler');
	
	// index page
	elgg_register_plugin_hook_handler('route', 'all', '\ColdTrick\WidgetManager\Router::routeIndex');
			
	// add extra widget pages
	$extra_contexts = elgg_get_plugin_setting('extra_contexts', 'widget_manager');
	if ($extra_contexts) {
		$contexts = string_to_tag_array($extra_contexts);
		if ($contexts) {
			foreach ($contexts as $context) {
				elgg_register_page_handler($context, 'widget_manager_extra_contexts_page_handler');
			}
		}
	}
		
	elgg_register_plugin_hook_handler('action', 'plugins/settings/save', 'widget_manager_plugins_settings_save_hook_handler');
	elgg_register_plugin_hook_handler('action', 'widgets/add', 'widget_manager_widgets_action_hook_handler');
	elgg_register_plugin_hook_handler('action', 'widgets/move', 'widget_manager_widgets_action_hook_handler');
	
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'widget_manager_permissions_check_object_hook_handler');

	elgg_register_plugin_hook_handler('view_vars', 'admin/appearance/default_widgets', '\ColdTrick\WidgetManager\DefaultWidgets::defaultWidgetsViewVars');
	elgg_register_plugin_hook_handler('view_vars', 'page/layouts/widgets', '\ColdTrick\WidgetManager\Layouts::checkFixedWidgets');

	elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\WidgetManager\Menus::registerAdminPageMenu');
	
	elgg_register_event_handler('create', 'object', 'widget_manager_create_object_handler');

	elgg_register_event_handler('cache:flush', 'system', '\ColdTrick\WidgetManager\Cache::resetWidgetsCache');
	
	elgg_register_event_handler('all', 'object', 'widget_manager_update_widget', 1000); // is only a fallback

	elgg_register_ajax_view('page/layouts/widgets/add_panel');
	elgg_register_ajax_view('widget_manager/widgets/settings');
	elgg_register_ajax_view('widgets/user_search/content');
	
	// register actions
	elgg_register_action('widget_manager/manage_widgets', $base_dir . '/actions/manage_widgets.php', 'admin');
	elgg_register_action('widget_manager/widgets/toggle_fix', $base_dir . '/actions/widgets/toggle_fix.php', 'admin');
	elgg_register_action('widget_manager/force_tool_widgets', $base_dir . '/actions/force_tool_widgets.php', 'admin');
	elgg_register_action('widget_manager/widgets/toggle_collapse', $base_dir . '/actions/widgets/toggle_collapse.php');
}

/**
 * Used to perform initialization of the group widgets features.
 *
 * @return void
 */
function widget_manager_init_group() {
	if (!elgg_is_active_plugin('groups')) {
		return;
	}
	
	$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
	if (!in_array($group_enable, ['yes', 'forced'])) {
		return;
	}

	elgg_extend_view('groups/edit', 'widget_manager/forms/groups_widget_access');
	elgg_register_action('widget_manager/groups/update_widget_access', $base_dir . '/actions/groups/update_widget_access.php');
		
	// cleanup widgets in group context
	elgg_extend_view('page/layouts/widgets/add_panel', 'widget_manager/group_tool_widgets', 400);
		
	if ($group_enable == 'yes') {
		// add the widget manager tool option
		$group_option_enabled = false;
		if (elgg_get_plugin_setting('group_option_default_enabled', 'widget_manager') == 'yes') {
			$group_option_enabled = true;
		}

		if (elgg_get_plugin_setting('group_option_admin_only', 'widget_manager') != 'yes') {
			// add the tool option for group admins
			add_group_tool_option('widget_manager', elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
		} elseif (elgg_is_admin_logged_in()) {
			add_group_tool_option('widget_manager', elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
		} elseif ($group_option_enabled) {
			// register event to make sure newly created groups have the group option enabled
			elgg_register_event_handler('create', 'group', 'widget_manager_create_group_event_handler');
		}
	}
		
	// register event to make sure all groups have the group option enabled if forces
	// and configure tool enabled widgets
	elgg_register_event_handler('update', 'group', 'widget_manager_update_group_event_handler');
		
	// make default widget management available
	elgg_register_plugin_hook_handler('get_list', 'default_widgets', 'widget_manager_group_widgets_default_list');
}

/**
 * Used to perform initialization of the multi_dashboard features.
 *
 * @return void
 */
function widget_manager_init_multi_dashboard() {
	// multi dashboard support
	add_subtype('object', MultiDashboard::SUBTYPE, 'MultiDashboard');
	
	if (!elgg_is_logged_in() || !widget_manager_multi_dashboard_enabled()) {
		return;
	}
	
	elgg_register_ajax_view('widget_manager/forms/multi_dashboard');

	elgg_register_plugin_hook_handler('route', 'dashboard', '\ColdTrick\WidgetManager\Router::routeDashboard');
	elgg_register_plugin_hook_handler('action', 'widgets/add', 'widget_manager_widgets_add_action_handler');

	elgg_register_action('multi_dashboard/edit', $base_dir . '/actions/multi_dashboard/edit.php');
	elgg_register_action('multi_dashboard/delete', $base_dir . '/actions/multi_dashboard/delete.php');
	elgg_register_action('multi_dashboard/drop', $base_dir . '/actions/multi_dashboard/drop.php');
	elgg_register_action('multi_dashboard/reorder', $base_dir . '/actions/multi_dashboard/reorder.php');
}
