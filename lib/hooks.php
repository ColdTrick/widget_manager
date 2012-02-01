<?php

	/**
	 * Returns a ACL for use in widgets
	 *
	 * @param $hook_name
	 * @param $entity_type
	 * @param $return_value
	 * @param $params
	 * @return unknown_type
	 */
	function widget_manager_write_access_hook($hook_name, $entity_type, $return_value, $params){
		$result = $return_value;
		
		if(elgg_in_context("widgets")){
			if(elgg_in_context("index") && elgg_is_admin_logged_in()){				
				// admins only have the following options for index widgets
				$result = array(
					ACCESS_PRIVATE => elgg_echo("access:admin_only"),
					ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
					ACCESS_LOGGED_OUT => elgg_echo("LOGGED_OUT"),
					ACCESS_PUBLIC => elgg_echo("PUBLIC")
				);
				
			} elseif(elgg_in_context("groups")) {
				$group = elgg_get_page_owner_entity();
				if(!empty($group->group_acl)){
					$result = array(
						ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
						ACCESS_PUBLIC => elgg_echo("PUBLIC"),
						$group->group_acl => elgg_echo("groups:group") . ": " . $group->name
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
	
		if(!elgg_is_logged_in() || elgg_is_admin_logged_in()){
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
		if(elgg_is_admin_logged_in() && elgg_get_plugin_setting("disable_free_html_filter", "widget_manager") == "yes"){
			$guid = get_input("guid");

			if($widget = get_entity($guid)){
				if($widget instanceof ElggWidget){
					if(($widget->context == "index") && ($widget->handler == "free_html")){
						elgg_unregister_plugin_hook_handler("validate", "input", "htmlawed_filter_tags");
					}
				}
			}
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
	
		if(empty($result) && ($setting = elgg_get_plugin_setting("custom_index", "widget_manager"))){
			list($non_loggedin, $loggedin) = explode("|", $setting);
				
			if((!elgg_is_logged_in() && !empty($non_loggedin)) || (elgg_is_logged_in() && !empty($loggedin)) || (elgg_is_admin_logged_in() && (get_input("override") == true))){
				include(elgg_get_plugins_path() . "/widget_manager/pages/custom_index.php");
				$result = true;
			}
		}
	
		return $result;
	}
	
	/*
	* adds an optional fix link to the menu
	*/
	function widget_manager_register_widget_menu($hook_name, $entity_type, $return, $params){
		$widget = $params['entity'];
		
		if(elgg_is_admin_logged_in() && elgg_in_context("default_widgets") && in_array($widget->context, array("profile", "dashboard")) && $widget->fixed_parent_guid){
			$class = "widget-manager-fix";
			if($widget->fixed){
				$class .= " fixed";
			}
			$params = array(
					'name' => "fix",
					'text' => elgg_view_icon('widget-manager-push-pin'),
					'title' => elgg_echo('widget_manager:widgets:fix'),
					'href' => "#$widget->guid",
					'class' => $class
			);
				
			$item = ElggMenuItem::factory($params);
			$return[] = $item;
		}
	
		return $return;
	}
	
	/*
	 * optionally removes the edit and delete links from the menu
	*/
	function widget_manager_prepare_widget_menu($hook_name, $entity_type, $return, $params){
		if(is_array($return)){
			$widget = $params["entity"];
			if($widget->fixed && !elgg_in_context("default_widgets")){
				foreach($return as $section_key => $section){
					foreach($section as $item_key => $item){
						if(in_array($item->getName(), array("delete", "settings"))){
							unset($return[$section_key][$item_key]);
						}
					}
				}
			}
		}
		return $return;
	}
	
	function widget_manager_dashboard_route_handler($hook_name, $entity_type, $return, $params){
		
		if($page = elgg_extract("segments", $return)){
			if(!empty($page[0])){
				set_input("multi_dashboard_guid", $page[0]);
			}
		}
	}
	