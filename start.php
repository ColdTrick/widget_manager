<?php 

	define("ACCESS_LOGGED_OUT", -5);
	
	require_once(dirname(__FILE__) . "/lib/deprecated.php");
	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");

	function widget_manager_plugins_boot(){
		// Load widgets
		widget_manager_load_widgets();
	}
	
	function widget_manager_init(){
		elgg_trigger_event("widgets_init", "widget_manager");
		
		if(elgg_is_active_plugin("groups") && (elgg_get_plugin_setting("group_enable", "widget_manager") == "yes")){
			// add the widget manager tool option
			
			$group_option_enabled = false;
			if(elgg_get_plugin_setting("group_option_default_enabled", "widget_manager") == "yes"){
				$group_option_enabled = true;	
			}
			
			if(elgg_get_plugin_setting("group_option_admin_only", "widget_manager") != "yes"){
				// add the tool option for group admins
				add_group_tool_option('widget_manager',elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
			} elseif(elgg_is_admin_logged_in()) {
				add_group_tool_option('widget_manager',elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
			} elseif($group_option_enabled) {
				// register event to make sure newly created groups have the group option enabled
				elgg_register_event_handler("create", "group", "widget_manager_create_group_event_handler");
			}
		}
		
		// extend CSS
		elgg_extend_view("css/elgg", "widget_manager/css/global");
		elgg_extend_view("css/admin", "widget_manager/css/global");
		elgg_extend_view("js/elgg", "widget_manager/js/site");
		elgg_extend_view("js/admin", "widget_manager/js/admin");
		
		// register a widget title url handler
		elgg_register_entity_url_handler("object", "widget", "widget_manager_widget_url_handler");	
	}

	function widget_manager_pagesetup(){
		
		$context = elgg_get_context();
		
		if(elgg_is_admin_logged_in() && $context == "admin"){
			// move defaultwidgets menu item
			elgg_unregister_menu_item("page", "appearance:default_widgets");
			elgg_register_menu_item('page', array(
					'name' => "appearance:default_widgets",
					'href' => "admin/appearance/default_widgets",
					'text' => elgg_echo("admin:appearance:default_widgets"),
					'context' => 'admin',
					'parent_name' => "widgets",
					'section' => "configure"
			));
			
			// add own menu items
			elgg_register_admin_menu_item('configure', 'manage', 'widgets');
			elgg_register_admin_menu_item('administer', 'widgets', 'statistics');
			
			if(elgg_get_plugin_setting("custom_index", "widget_manager") == "1|0"){	
				// a special link to manage homepages that are only available if logged out
				elgg_register_menu_item('page', array(
									'name' => "admin:widgets:manage:index",
									'href' => elgg_get_site_url() . "?override=true",
									'text' => elgg_echo("admin:widgets:manage:index"),
									'context' => 'admin',
									'parent_name' => "widgets",
									'section' => "configure"
				));
			}
		}
		
		if(widget_manager_valid_context($context)){
			elgg_trigger_event("widgets_pagesetup", "widget_manager");
		}
	}
	
	/* enables widget that are not specifically registered for groups or index widget, but do work */
	function widget_manager_reset_widget_context(){
		global $CONFIG;
		
		$allowed_group_widgets = array("bookmarks", "pages", "blog", "twitter");
		$allowed_index_widgets = array("twitter");
		
		if(is_array($CONFIG->widgets->handlers)){
			foreach($allowed_group_widgets as $handler){
				if(array_key_exists($handler, $CONFIG->widgets->handlers)){
					
					$CONFIG->widgets->handlers[$handler]->context[] = "groups";
					
				}
			}
			
		}
	}
	
	// register default Elgg events
	elgg_register_event_handler("plugins_boot", "system", "widget_manager_plugins_boot");
	elgg_register_event_handler("init", "system", "widget_manager_init");
	elgg_register_event_handler("init", "system", "widget_manager_reset_widget_context",9999); // needs to be last
	elgg_register_event_handler("pagesetup", "system", "widget_manager_pagesetup");
		
	// register plugin hooks
	elgg_register_plugin_hook_handler("access:collections:write", "user", "widget_manager_write_access_hook");
	elgg_register_plugin_hook_handler("access:collections:read", "user", "widget_manager_read_access_hook");
	elgg_register_plugin_hook_handler("action", "widgets/save", "widget_manager_widgets_save_hook");
	elgg_register_plugin_hook_handler('index', 'system', 'widget_manager_custom_index', 50); // must be very early
	
	// register actions
	elgg_register_action("widget_manager/manage", dirname(__FILE__) . "/actions/manage.php", "admin");
	elgg_register_action("widget_manager/widgets/toggle_fix", dirname(__FILE__) . "/actions/widgets/toggle_fix.php", "admin");
