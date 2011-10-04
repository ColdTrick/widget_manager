<?php 

	global $CONFIG;
	
	define("ACCESS_LOGGED_OUT", -5);
	
	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/classes.php");

	function widget_manager_plugins_boot(){
		// Load widgets
		widget_manager_load_widgets();
	}
	
	function widget_manager_init(){
		global $CONFIG;
		
		// version upgrade
		if(isadminloggedin()){
			run_function_once("widget_manager_run_once_3_0");
		}
		
		// fix class handler
		if(get_subtype_class("object", "widget") != "ElggWidget"){
			run_function_once("widget_manager_run_once_class");
		}
		
		trigger_elgg_event("widgets_init", "widget_manager");
		
		if(get_plugin_setting("group_enable", "widget_manager") == "yes" && is_plugin_enabled("groups")){
			// add the widget manager tool option
			
			$group_option_enabled = false;
			if(get_plugin_setting("group_option_default_enabled", "widget_manager") == "yes"){
				$group_option_enabled = true;	
			}
			
			if(get_plugin_setting("group_option_admin_only", "widget_manager") != "yes"){
				// add the tool option for group admins
				add_group_tool_option('widget_manager',elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
			} elseif(isadminloggedin()) {
				add_group_tool_option('widget_manager',elgg_echo('widget_manager:groups:enable_widget_manager'), $group_option_enabled);
			} elseif($group_option_enabled) {
				// register event to make sure newly created groups have the group option enabled
				register_elgg_event_handler("create", "group", "widget_manager_create_group_event_handler");
			}
			
			set_view_location("groups/profileitems", $CONFIG->pluginspath . "widget_manager/views_custom/");
		}
		
		// extend CSS
		elgg_extend_view("css", "widget_manager/css");
		elgg_extend_view("css", "fancybox/css");
		elgg_extend_view("js/initialise_elgg", "widget_manager/js");
		
		// register page handler for nice URLs
		register_page_handler("widget_manager", "widget_manager_page_handler");
		
		if(is_plugin_enabled("defaultwidgets")){
			// overrule page handler of defaultwidgets
			register_page_handler("defaultwidgets", "widget_manager_defaultwidgets_page_handler");
		}
		
		// register a new context for widgets
		use_widgets("index");
		use_widgets("groups");
		use_widgets("default_profile");
		use_widgets("default_dashboard");
		
		// add titles to the widgets
		widget_manager_widget_titles();
		
		// overrule widgets/reorder action
		register_action("widgets/reorder", false, dirname(__FILE__) . "/actions/widgets/reorder.php");
	}

	function widget_manager_pagesetup(){
		global $CONFIG;
		
		$context = get_context();
		
		if(widget_manager_valid_context($context)){
			trigger_elgg_event("widgets_pagesetup", "widget_manager");
		}
		
		if(isadminloggedin() && $context == "admin"){
			// remove menu items from defaultwidgets
			if(is_plugin_enabled("defaultwidgets")){
				$submenu = $CONFIG->submenu;
				
				foreach($submenu as $group => $items){
					if(!empty($items)){
						foreach($items as $index => $item){
							if($item->name == elgg_echo("defaultwidgets:menu:profile") || $item->name == elgg_echo("defaultwidgets:menu:dashboard")){
								unset($submenu[$group][$index]);
							}
						}
					}
				}
				
				$CONFIG->submenu = $submenu;
			}
			
			// add own menu items
			add_submenu_item(elgg_echo("widget_manager:menu:manage"), $CONFIG->wwwroot . "pg/widget_manager/manage", "w");
			
			add_submenu_item(elgg_echo("widget_manager:menu:dashboard"), $CONFIG->wwwroot . "pg/widget_manager/dashboard", "w");
			add_submenu_item(elgg_echo("widget_manager:menu:profile"), $CONFIG->wwwroot . "pg/widget_manager/profile", "w");
			
			if($setting = get_plugin_setting("custom_index", "widget_manager")){
				if($setting == "1|0"){
					// a special link to manage homepages that are only available if logged out
					add_submenu_item(elgg_echo("widget_manager:menu:index"), $CONFIG->wwwroot . "pg/widget_manager/custom_index", "w");
				}
			}
		}
		
		if(widget_manager_valid_context($context)){
			// check if widget presence needs to be updated
			if(get_input("shell") != "no"){
				// no action required when in display widgets
				widget_manager_update_widgets($context, page_owner_entity());
			}
			
			// js for dragging/adding of widgets
			elgg_extend_view('metatags', 'widget_manager/metatags');
		}
	}
	
	function widget_manager_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "dashboard":
			case "profile":
				set_input("widget_context", $page[0]);
				
				include(dirname(__FILE__) . "/pages/default.php");
				break;
			case "manage":
				set_input("widget_context", $page[0]);
				
				include(dirname(__FILE__) . "/pages/manage.php");
				break;
			case "widgets":
				switch($page[1]){
					case "lightbox":
						include(dirname(__FILE__) . "/procedures/widgets/lightbox.php");
						break;
					case "add":
						include(dirname(__FILE__) . "/procedures/widgets/add.php");
						break;
				}
			
				break;
			case "custom_index":
				include(dirname(__FILE__) . "/pages/custom_index.php");
				break;
			default:
				// you came here with a invalid url
				forward($CONFIG->wwwroot . "pg/widget_manager/manage");
				break;
		}
	}
	
	/**
	 * Page handler
	 * 
	 * @param $page
	 * @return unknown_type
	 */
	function widget_manager_defaultwidgets_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "dashboard":
				forward($CONFIG->wwwroot . "pg/widget_manager/dashboard");
				break;
			case "profile":
			default:
				forward($CONFIG->wwwroot . "pg/widget_manager/profile");
				break;
		}
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
		
		if(empty($result) && ($setting = get_plugin_setting("custom_index", "widget_manager"))){
			list($non_loggedin, $loggedin) = explode("|", $setting);
			
			if((!isloggedin() && !empty($non_loggedin)) || (isloggedin() && !empty($loggedin))){
				include(dirname(__FILE__) . "/pages/custom_index.php");
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	 * Filters widgets registered on 'all' that are not working on index/groups context
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 * @return unknown_type
	 */
	function widget_manager_widget_context_filter($hook_name, $entity_type, $return_value, $params){
		$result = $return_value;
		
		if(!empty($params) && is_array($params)){
			if(array_key_exists("handler", $params) && array_key_exists("widget", $params) && array_key_exists("context", $params)){
				$context = $params["context"];
				$handler = $params["handler"];
				$widget = $params["widget"];
				
				$allowed_index_handlers = array("twitter", "twitter_search", "content_by_tag", "event_calendar");
				$allowed_groups_handlers = array("bookmarks", "pages", "twitter_search", "blog", "twitter");
				
				switch($context){
					case "index":
						if(in_array($handler, $allowed_index_handlers)){
							$result = false;
						}
						break;
					case "groups":
						if(in_array($handler, $allowed_groups_handlers)){
							$result = false;
						}
						break;
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Returns a ACL for use in widgets 
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 * @return unknown_type
	 */
	function widget_manager_widget_access_hook($hook_name, $entity_type, $return_value, $params){
		
		$result = $return_value;
		
		if($entity = $params["entity"]){
			if($entity_type == "site"){
				if($entity->context == "default_profile" || $entity->context == "default_dashboard"){
					$result = array(
						ACCESS_PRIVATE => elgg_echo("PRIVATE"),
						ACCESS_FRIENDS => elgg_echo("access:friends:label"),
						ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
						ACCESS_PUBLIC => elgg_echo("PUBLIC")
					);
				} elseif(isadminloggedin()){
					$result = array(
						ACCESS_PRIVATE => elgg_echo("access:admin_only"),
						ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
						ACCESS_LOGGED_OUT => elgg_echo("LOGGED_OUT"),
						ACCESS_PUBLIC => elgg_echo("PUBLIC")
					);
				}
			} elseif($entity_type == "group") {
				$group = $entity->getOwnerEntity();
				if($acl = get_access_collection($group->group_acl)){
					$result = array(
						ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
						ACCESS_PUBLIC => elgg_echo("PUBLIC"),
						$acl->id => $acl->name
					);
				}
			}
		}
		
		return $result;
	}
	
	
	/**
	 * Creates the ability to see content only for logged_out users
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 * @return unknown_type
	 */
	function widget_manager_read_access_hook($hook_name, $entity_type, $return_value, $params){
		$result = $return_value;
		
		if(!isloggedin() || isadminloggedin()){
			if(!empty($result) && !is_array($result)){
				$result = array($result);
			} elseif(empty($result)){
				$result = array();
			}
			
			if(is_array($result)){
				$result[] = ACCESS_LOGGED_OUT;
			}
		}
		
		return $result;
	}
	
	/**
	 * Function that unregisters html validation for admins to be able to save freehtml widgets with special html
	 * 
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 */
	function widget_manager_widgets_save_hook($hook_name, $entity_type, $return_value, $params){
		if(isadminloggedin()){
			if(get_plugin_setting("disable_free_html_filter", "widget_manager") == "yes"){
				$guid = get_input("guid");
				
				if($widget = get_entity($guid)){
					if($widget->getSubtype() == "widget"){
						if(($widget->context == "index") && ($widget->handler == "free_html")){
							unregister_plugin_hook("validate", "input", "htmlawed_filter_tags");
						}
					}
				}
			}
		}
	}
	
	// register default Elgg events
	register_elgg_event_handler("plugins_boot", "system", "widget_manager_plugins_boot");
	register_elgg_event_handler("init", "system", "widget_manager_init");
	register_elgg_event_handler("pagesetup", "system", "widget_manager_pagesetup");
	
	// register create user event hook
	register_elgg_event_handler("create", "user", "widget_manager_new_user");
	
	// register plugin hooks
	register_plugin_hook("widget_manager:context:filter", "widget", "widget_manager_widget_context_filter");
	register_plugin_hook("widget_manager:widget:access", "all", "widget_manager_widget_access_hook", 1); // need to be first
	register_plugin_hook("access:collections:read", "all", "widget_manager_read_access_hook", 9999); // need to be the last
	
	register_plugin_hook("action", "widgets/save", "widget_manager_widgets_save_hook"); // need to be the last
	
	// register on custom index
	register_plugin_hook('index', 'system', 'widget_manager_custom_index', 50); // must be very early
	
	// register actions
	register_action("widget_manager/manage", false, dirname(__FILE__) . "/actions/manage.php", true);
	register_action("widget_manager/widgets/toggle_fix", false, dirname(__FILE__) . "/actions/widgets/toggle_fix.php", true);
	register_action("widget_manager/update_timestamp", false, dirname(__FILE__) . "/actions/update_timestamp.php", true);

?>