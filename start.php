<?php 

	global $CONFIG;
	
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
		global $CONFIG;
		
		elgg_trigger_event("widgets_init", "widget_manager");
		
		if(elgg_get_plugin_setting("group_enable", "widget_manager") == "yes" && elgg_is_active_plugin("groups")){
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
			
			elgg_set_view_location("groups/profileitems", $CONFIG->pluginspath . "widget_manager/views_custom/");
		}
		
		// extend CSS
		elgg_extend_view("css/elgg", "widget_manager/css/global");
		elgg_extend_view("css/admin", "widget_manager/css/global");
		elgg_extend_view("js/elgg", "widget_manager/js/site");
		elgg_extend_view("js/admin", "widget_manager/js/admin");
		
		// register page handler for nice URLs
		elgg_register_page_handler("widget_manager", "widget_manager_page_handler");
		
		// register a widget title url handler
		elgg_register_entity_url_handler("object", "widget", "widget_manager_widget_url_handler");
		
	}

	function widget_manager_pagesetup(){
		global $CONFIG;
		
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
			
			if(elgg_get_plugin_setting("custom_index", "widget_manager") == "1|0"){	
				// a special link to manage homepages that are only available if logged out
				add_submenu_item(elgg_echo("widget_manager:menu:index"), $CONFIG->wwwroot . "widget_manager/custom_index", "w");
				elgg_register_admin_menu_item('configure', 'index', 'widgets');
			}
		}
		
		if(widget_manager_valid_context($context)){
			elgg_trigger_event("widgets_pagesetup", "widget_manager");
		}
	}
	
	function widget_manager_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "manage":
				set_input("widget_context", $page[0]);
				
				include(dirname(__FILE__) . "/pages/manage.php");
				break;
			case "custom_index":
				include(dirname(__FILE__) . "/pages/custom_index.php");
				break;
			default:
				// you came here with a invalid url
				forward("widget_manager/manage");
				break;
		}
		return true;
	}
		
	/**
	 * Hook to take over the index page
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $parameters
	 * @return unknown_type
	 */
	function widget_manager_custom_index($hook_name, $entity_type, $return_value, $parameters){
		$result = $return_value;
		
		if(empty($result) && ($setting = elgg_get_plugin_setting("custom_index", "widget_manager"))){
			list($non_loggedin, $loggedin) = explode("|", $setting);
			
			if((!elgg_is_logged_in() && !empty($non_loggedin)) || (elgg_is_logged_in() && !empty($loggedin))){
				include(dirname(__FILE__) . "/pages/custom_index.php");
				$result = true;
			}
		}
		
		return $result;
	}
	
	// register default Elgg events
	elgg_register_event_handler("plugins_boot", "system", "widget_manager_plugins_boot");
	elgg_register_event_handler("init", "system", "widget_manager_init");
	elgg_register_event_handler("pagesetup", "system", "widget_manager_pagesetup");
		
	// register plugin hooks
	elgg_register_plugin_hook_handler("widget_manager:widget:access", "all", "widget_manager_widget_access_hook", 1); // need to be first
	elgg_register_plugin_hook_handler("access:collections:read", "all", "widget_manager_read_access_hook", 9999); // need to be the last
	
	elgg_register_plugin_hook_handler("action", "widgets/save", "widget_manager_widgets_save_hook");
	
	// register on custom index
	elgg_register_plugin_hook_handler('index', 'system', 'widget_manager_custom_index', 50); // must be very early
	
	// register actions
	elgg_register_action("widget_manager/manage", dirname(__FILE__) . "/actions/manage.php", "admin");
	elgg_register_action("widget_manager/widgets/toggle_fix", dirname(__FILE__) . "/actions/widgets/toggle_fix.php", "admin");
	elgg_register_action("widget_manager/update_timestamp", dirname(__FILE__) . "/actions/update_timestamp.php", "admin");
