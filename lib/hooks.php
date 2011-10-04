<?php

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