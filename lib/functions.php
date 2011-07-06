<?php 

	function widget_manager_get_widget_setting($widget_handler, $setting, $context = null){
		$result = false;
		
		if(is_null($context)){
			$context = get_context();
		}
		
		if(widget_manager_valid_context($context)){
			if($plugin_setting = get_plugin_setting($context . "_" . $widget_handler . "_" . $setting, "widget_manager")){
				if($plugin_setting == "yes"){
					$result = true;
				}
			} elseif($setting == "allow_multiple") {
				static $widgets;
				
				if(empty($widgets)){
					// TODO: needs to be fixed in core (trac #2779) -->> fixed in 1.7.7 
					global $CONFIG;
					$backup = $CONFIG->widgets->handlers;
					
					$widgets = array();
					$widgets["dashboard"] = widget_manager_get_widgets("dashboard");
					$CONFIG->widgets->handlers = $backup;
					
					$widgets["profile"] = widget_manager_get_widgets("profile");
					$CONFIG->widgets->handlers = $backup;
					
					$widgets["index"] = widget_manager_get_widgets("index");
					$CONFIG->widgets->handlers = $backup;
					
					$widgets["groups"] = widget_manager_get_widgets("groups");
					$CONFIG->widgets->handlers = $backup;
					
					$widgets["default_profile"] = widget_manager_get_widgets("default_profile");
					$CONFIG->widgets->handlers = $backup;
					
					$widgets["default_dashboard"] = widget_manager_get_widgets("default_dashboard");
					$CONFIG->widgets->handlers = $backup;
				}
				
				if(!empty($widgets[$context])){
					foreach($widgets[$context] as $handler => $widget){
						if($handler == $widget_handler){
							$result = $widget->multiple;
						}
					}
				}
			} elseif($setting == "can_add" || $setting == "can_remove"){
				$result = true;
			}
		}
		
		return $result;
	}
	
	function widget_manager_set_widget_setting($widget_handler, $setting, $context, $value){
		$result = false;
		
		if(!empty($widget_handler) && !empty($setting) && widget_manager_valid_context($context)){
			$widget_setting = $context . "_" . $widget_handler . "_" . $setting;
			
			if(set_plugin_setting($widget_setting, $value, "widget_manager")){
				$result = true;
			}
		}
		
		return $result;
	}
	
	function widget_manager_get_widgets($context){
		$result = false;

		if(widget_manager_valid_context($context)){
						
			$old_context = get_context();
			set_context($context);
			
			$result = get_widget_types();
			
			widget_manager_filter_widgets($result, $context);
			
			set_context($old_context);
		}
		
		return $result;
	}
	
	function widget_manager_get_user_widgets($context = "", $user_guid = 0, $position = "all"){
		$result = false;
		
		if(empty($context)){
			$context = get_context();
		}
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if ($widgets = get_entities_from_private_setting_multi(array('context' => $context), "object", "widget", $user_guid, "", false)){
			$widgetorder = array();
			
			foreach($widgets as $widget) {
				if(
					($position == "fixed" && $widget->fixed) ||
					($position == "free" && !$widget->fixed) ||
					($position == "all")){
					$order = $widget->order;
					if(!is_numeric($order)){
						$order = 0;
					}
					while(isset($widgetorder[$order])) {
						$order++;
					}
					$widgetorder[$order] = $widget;
				} 
			}
			
			ksort($widgetorder);
			
			$result = $widgetorder;
		}
		
		return $result;
	}
	
	if(!function_exists("add_widget_title_link")){ // to prevent conflicts with active widget_titles plugin
		/**
		 * Register a widget title 
		 * 
		 * @param $handler
		 * @param $link
		 */
		function add_widget_title_link($handler, $link){
			global $CONFIG;
			
			if (!empty($handler) && !empty($link)) {
				if (isset($CONFIG->widgets) && isset($CONFIG->widgets->handlers) && isset($CONFIG->widgets->handlers[$handler])) {
					$CONFIG->widgets->handlers[$handler]->link = $link;
				}	
			}
		}
	}
	
	/**
	 * Return widgets configured for a specific context
	 * 
	 * @param $context
	 * @param $position
	 * @return false | array of widgets
	 */
	function widget_manager_get_configured_widgets($context, $position = "all"){
		global $CONFIG;
		
		$result = false;
		
		if(widget_manager_valid_context($context) && ($context == "profile" || $context == "dashboard")){
			// default widgets are created in a special context
			$options = array('context' => "default_" . $context);
			
			// default widgets are owned by the site
			$result = get_entities_from_private_setting_multi($options, "object", "widget", $CONFIG->site_guid, "e.time_created asc", false);
			
			if(!empty($result)){
				if($position == "fixed" || $position == "free"){
					foreach($result as $key => $widget){
						if($position == "fixed" && !$widget->fixed){
							// remove free widgets
							unset($result[$key]);							
						} elseif($position == "free" && $widget->fixed){
							// remove fixed widgets
							unset($result[$key]);
						}						
					}
				}
			}
		}
		return $result;
	}
	
	function widget_manager_sort_widgets(&$widgets){
		if(!empty($widgets)){
			foreach($widgets as $key => $row){
				$name[$key] = $row->name; 
			}
			
			array_multisort($name, SORT_STRING, $widgets);
		}
	}
	
	function widget_manager_set_configured_widgets($context, $column, $value){
		$result = false;
		
		if(widget_manager_valid_context($context) && !empty($column)){
			if(set_plugin_setting($context . "_" . $column, $value, "widget_manager")){
				$result = true;
			}
		}
		
		return $result;
	}
	
	function widget_manager_update_widgets($context, ElggUser $user){
		$result = true;
		
		if(widget_manager_valid_context($context) && ($context == "dashboard" || $context == "profile") && $user instanceof ElggUser){
			// ignore access when creating new widgets
			$ignore_access = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
			$plugin_fixed_time = get_plugin_setting($context . "_enforce_fixed", "widget_manager");
			$user_fixed_time = get_plugin_usersetting($context . "_enforce_fixed", $user->getGUID(), "widget_manager");
			
			if($user_fixed_time < $plugin_fixed_time){
				if(widget_manager_update_widgets_fixed($context, $user)){
					set_plugin_usersetting($context . "_enforce_fixed", time(), $user->getGUID(), "widget_manager");
					$fix_order = true;
				} else {
					$result = false;
				}
			}
				
			$plugin_free_time = get_plugin_setting($context . "_enforce_free", "widget_manager");
			$user_free_time = get_plugin_usersetting($context . "_enforce_free", $user->getGUID(), "widget_manager");
			if($user_free_time < $plugin_free_time){
				
				if(widget_manager_update_widgets_free($context, $user)){
					set_plugin_usersetting($context . "_enforce_free", time(), $user->getGUID(), "widget_manager");
					$fix_order = true;
				} else {
					$result = false;
				}
			}
			
			if($fix_order){
				widget_manager_fix_widget_order($context, $user);
			}
			
			if(($ignore_access !== true)){
				elgg_set_ignore_access(false);
			}
		}
		return $result;
	}
	
	function widget_manager_update_widgets_fixed($context, ElggUser $user){
		if(widget_manager_valid_context($context) && $user instanceof ElggUser){
			$configured_widgets = widget_manager_get_configured_widgets($context, "fixed");
			$current_fixed_widgets = widget_manager_get_user_widgets($context, $user->getGUID(), "fixed");
			
			$configured_fixed_widget_guids = array();
			
			if(!empty($configured_widgets)){
				// update or add widgets
				foreach($configured_widgets as $configured_widget){
					$parent_guid = $configured_widget->getGUID();

					$options = array(
							'context' => $context,
							'fixed_parent_guid' => $configured_widget->getGUID()
							);
					
					// search configured widgets with this parent
					$result = get_entities_from_private_setting_multi($options, "object", "widget", $user->getGUID(), "", false);

					if(!empty($result)){
						// update settings of already existing plugin
						$current_widget = $result[0];
						$configured_fixed_widget_guids[] = $current_widget->getGUID();
						
						widget_manager_copy_widget_settings($configured_widget, $current_widget);
					} else {
						// create the new widget
						$new_widget = widget_manager_copy_widget_to_user($configured_widget, $user);
						$configured_fixed_widget_guids[] = $new_widget->getGUID();
					}					
				}
			}
			
			// remove fixed status from existing widgets
			if(!empty($current_fixed_widgets)){
				foreach($current_fixed_widgets as $current_fixed_widget){
					if(!in_array($current_fixed_widget->getGUID(), $configured_fixed_widget_guids)){
						$current_fixed_widget->fixed = false;
					}
				}	
			}
		}
		
		return true;
	}
	
	function widget_manager_update_widgets_free($context, ElggUser $user){
		if(widget_manager_valid_context($context) && $user instanceof ElggUser){
			$widgets = widget_manager_get_configured_widgets($context, "free");
			
			if(!empty($widgets)){
				
				$to_place_widgets = array();
			
				foreach($widgets as $widget){
					$handler = $widget->handler;
					if(widget_type_exists($handler)){
						if(!array_key_exists($handler, $to_place_widgets)){
							$to_place_widgets[$handler] = array(
								"count" => 0,
								"columns" => array(
									1 => array(),
									2 => array(),
									3 => array()
									)
							);
						} 
						
						$to_place_widgets[$handler]["count"]++;
						$to_place_widgets[$handler]["columns"][$widget->column][] = $widget;
					}
				}
				
				if(!empty($to_place_widgets)){
					
					$all_user_widgets = widget_manager_get_user_widgets($context, $user->getGUID());
				
					if(!empty($all_user_widgets)){
						foreach($all_user_widgets as $widget){
							if(!$widget->fixed){ // ignore fixed widgets
								if(array_key_exists($widget->handler, $to_place_widgets)){
									if($to_place_widgets[$widget->handler]["count"] > 1){
										// reduce number of new widgets
										$to_place_widgets[$widget->handler]["count"]--;
										
										// remove first element from widget column
										array_shift($to_place_widgets[$widget->handler]["columns"][$widget->column]);
									} else {
										unset($to_place_widgets[$widget->handler]);
									}
								}
							}
						}
					}
					
					if(!empty($to_place_widgets)){
						
						foreach($to_place_widgets as $handler){
							
							if($handler["count"] > 0){
								foreach($handler["columns"] as $col => $new_widgets){
									if(!empty($new_widgets)){
										foreach($new_widgets as $new_widget){
											
											widget_manager_copy_widget_to_user($new_widget, $user);
											$handler["count"]--;
											if($handler["count"] == 0){
												break(2); // done with the current handler
											}
										}
									}									
								}	
							}
						}
					}
				}
			}
		}
		
		return true;
	}
	
	function widget_manager_fix_widget_order($context, ElggUser $user){
		if(widget_manager_valid_context($context) && $user instanceof ElggUser){
			$new_order = 10;
			$widgets = widget_manager_get_user_widgets($context, $user->getGUID());
			
			$widget_configuration = array(
				1 => array("fixed" => array(), "free" => array()),
				2 => array("fixed" => array(), "free" => array()),
				3 => array("fixed" => array(), "free" => array())
				);
			
			if(!empty($widgets)){
				foreach($widgets as $order => $widget){
					$position = "free";
					if($widget->fixed){
						$position = "fixed";
					}
					$widget_configuration[$widget->column][$position][$order] = $widget;
				}
				
				// reset order for each column
				foreach($widget_configuration as $column => $positions){
					if(!empty($positions["fixed"])){
						ksort($positions["fixed"]);
						foreach($positions["fixed"] as $widget){
							$widget->order = $new_order;
							$new_order += 10;
						}
					}
					if(!empty($positions["free"])){
						ksort($positions["free"]);
						foreach($positions["free"] as $widget){
							$widget->order = $new_order;
							$new_order += 10;
						}
					}
				}
			}
		}
	}
	
	function widget_manager_valid_context($context){
		$result = false;
		
		$valid_contexts = array("profile", "dashboard", "index", "groups", "default_dashboard", "default_profile");
		
		if(!empty($context) && in_array($context, $valid_contexts)){
			$result = true;
		}
		
		return $result;
	}
	
	function widget_manager_create_widgets(ElggUser $user){
		$result = true;
		
		if(!empty($user)){
			$contexts = array("profile", "dashboard");
			
			// ignore access when creating new widgets
			$ignore_access = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
			foreach($contexts as $context){
				$configured_widgets = widget_manager_get_configured_widgets($context);
				foreach($configured_widgets as $widget){
					// clone this widget to the user
					widget_manager_copy_widget_to_user($widget, $user);
				}
			}
			
			if(($ignore_access !== true)){
				elgg_set_ignore_access(false);
			}
		}

		return $result;
	}
	
	function widget_manager_copy_widget_to_user(ElggWidget $widget, ElggUser $user){
		$result = false;
		
		// copy from default? replace context
		$context = str_replace("default_", "", $widget->context);
		
		// create the widget
		$new_widget = widget_manager_add_widget($user->getGUID(), $widget->handler, $context, $widget->order, $widget->column);
		if($new_widget){
			
			// copy widget settings
			widget_manager_copy_widget_settings($widget, $new_widget);
			
			if($widget->fixed){
				// register parent guid for fixed widgets 
				$new_widget->fixed_parent_guid = $widget->getGUID();
			}
			
			// reset access on new widget
			$new_widget->access_id = $widget->access_id;
			$new_widget->save();
			
			$result = $new_widget;
		}
		
		return $result;		
	}
	
	function widget_manager_copy_widget_settings(ElggWidget $from_widget, ElggWidget $to_widget){
		$result = false;
		
		if(!empty($from_widget) && !empty($to_widget)){
			$master_settings = get_all_private_settings($from_widget->getGUID());
			
			if(!empty($master_settings)){
				foreach($master_settings as $setting => $value){
					if($setting != "context"){
						$to_widget->$setting = $value;
					}
				}
			}
			$result = true;	
		}
		
		return $result;
	}
	
	function widget_manager_add_widget($user_guid, $handler, $context, $order, $column){
		$result = false;
		
		$widget = new ElggWidget();
		$widget->owner_guid = $user_guid;
		$widget->container_guid = $user_guid;
		$widget->access_id = ACCESS_PUBLIC;
		
		if($widget->save()){
			$widget->handler = $handler;
			$widget->context = $context;
			$widget->order = $order;
			$widget->column = $column;
			
			$widget->access_id = get_default_access(get_user($user_guid));
			
			if($widget->save()){
				$result = $widget;
			}
		}
		
		return $result;
	}
	
	function widget_manager_filter_widgets(&$widgets, $context = ""){
		$result = false;
		
		if(empty($context)){
			$context = get_context();
		}
		
		if(in_array($context, array("index", "groups")) && !empty($widgets)){
			$result = true;
			
			foreach($widgets as $handler => $widget){
				if(in_array("all", $widget->context)){
					$params = array(
						"handler" => $handler,
						"widget" => $widget, 
						"context" => $context
					);
					
					if(trigger_plugin_hook("widget_manager:context:filter", "widget", $params, true)){
						unset($widgets[$handler]);
					}
				}
			}
		}
				
		return $result;
	}
	
	function widget_manager_is_page_handler_registered($handler){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($handler)){
			if(array_key_exists($handler, $CONFIG->pagehandler)){
				if(is_callable($CONFIG->pagehandler[$handler])){
					$result = true;
				}
			}
		}
		
		return $result;
	}
	
	function widget_manager_widget_titles(){
		// configures some widget titles for non widgetmanager widgets
		add_widget_title_link("thewire", "[BASEURL]pg/thewire/owner/[USERNAME]");
		add_widget_title_link("friends", "[BASEURL]pg/friends/[USERNAME]");
		add_widget_title_link("album_view", "[BASEURL]pg/photos/owned/[USERNAME]");
		add_widget_title_link("latest", "[BASEURL]pg/photos/mostrecent/[USERNAME]");
		add_widget_title_link("latest_photos", "[BASEURL]pg/photos/mostrecent/[USERNAME]");
		add_widget_title_link("messageboard", "[BASEURL]pg/messageboard/[USERNAME]");		
		add_widget_title_link("a_users_groups", "[BASEURL]pg/groups/member/[USERNAME]");		
		add_widget_title_link("event_calendar", "[BASEURL]pg/event_calendar/");
		add_widget_title_link("filerepo", "[BASEURL]pg/file/owner/[USERNAME]");
		add_widget_title_link("pages", "[BASEURL]pg/pages/owned/[USERNAME]");
		add_widget_title_link("bookmarks", "[BASEURL]pg/bookmarks/owner/[USERNAME]");
		add_widget_title_link("izap_videos", "[BASEURL]pg/izap_videos/[USERNAME]"); 
		add_widget_title_link("river_widget", "[BASEURL]pg/activity/");
		add_widget_title_link("blog", "[BASEURL]pg/blog/owner/[USERNAME]");
	}
	
	function widget_manager_load_widgets(){
		global $CONFIG;
		
		$widgets_folder = $CONFIG->pluginspath . "widget_manager/widgets";
		$widgets_folder_contents = scandir($widgets_folder);
		 
		foreach($widgets_folder_contents as $widget){
			
			if(is_dir($widgets_folder . "/" . $widget) && $widget !== "." && $widget !== ".."){
				if(file_exists($widgets_folder . "/" . $widget . "/start.php")){
					$widget_folder = $widgets_folder . "/" . $widget; 
					
					// include start.php
 					include($widget_folder . "/start.php");
				} else {
 					elgg_log(elgg_echo("widgetmanager:load_widgets:missing_start"), "WARNING");
 				}	
			}
		}
	}
	
	function widget_manager_run_once_3_0(){
		global $CONFIG;
		set_time_limit(0);
		// convert group custom layout widget to widget manager widgets
		$group_conversion = array(
			"blog" => "group_blog",
			"bookmarks" => "bookmarks",
			"event_calendar" => "group_event_calendar",
			"files" => "group_files",
			"forum_topics" => "group_forum_topics",
			"free_html" => "free_html",
			"izap_videos" => "group_izap_videos",
			"pages" => "group_pages",
			"rss" => "group_rss",
			"tidypics" => "group_tidypics",
			"twitter" => "twitter",
			"videolist" => "group_videolist"
		);
		
		$types = array_keys($group_conversion);
		
		$options = array(
			"type" => "object",
			"subtype" => "group_widget",
			"limit" => false,
			"joins" => array("JOIN " . $CONFIG->dbprefix . "objects_entity oe ON e.guid = oe.guid"),
			"wheres" => array("oe.title IN ('" . implode("','", $types) . "')"),
			"site_guids" => false
		);
		
		if($widgets = elgg_get_entities($options)){
			foreach($widgets as $index => $widget){
				$layout = $widget->getEntitiesFromRelationship("group_widget_relation", true);
				if($layout){
					$layout = $layout[0];
					
					$column = 2;
					$order = 0;
					$found = false;
					
					if(!empty($layout->left_widgets)){
						$layout_order = explode(",", $layout->left_widgets);
						
						if(($place = array_search($widget->getGUID(), $layout_order)) !== false){
							$found = true;
							$order = $place + 1;
						}
					} 
					if(!$found && !empty($layout->right_widgets)){
						$layout_order = explode(",", $layout->right_widgets);
						
						if(($place = array_search($widget->getGUID(), $layout_order)) !== false){
							$found = true;
							$order = $place + 1;
							$column = 3;
						}
					}
					
					if($new_widget = widget_manager_add_widget($widget->getOwner(), $group_conversion[$widget->title], "groups", $order, $column)){
						$metadata = get_metadata_for_entity($widget->getGUID());
						
						if(!empty($metadata)){
							foreach($metadata as $setting){
								$name = $setting->name;
								$value = $setting->value;
								
								$new_widget->$name = $value;
							}
						}
						
						// change access to group access
						$new_widget->access_id = $widget->getOwnerEntity()->group_acl;
						
						if($widget->site_guid != $new_widget->site_guid){
							$new_widget->site_guid = $widget->site_guid;
						}
						
						if($new_widget->save()){
							$group = $widget->getOwnerEntity();
							if($group instanceof ElggGroup){
								$group->widget_manager_enable = "yes";
							}
							
							$widget->disable();
						}
						
						invalidate_cache_for_entity($new_widget->getGUID());
					}
					
					invalidate_cache_for_entity($layout->getGUID());
				}
			}
		}
		
		// conver index widget to new widgets
		$index_convert = array(
			"index_freehtml" => "free_html"
		);
		
		foreach($index_convert as $old_handler => $new_handler){
			$private_settings = array(
				"context" => "index",
				"handler" => $old_handler
			);
			
			if($widgets = get_entities_from_private_setting_multi($private_settings, "object", "widget", 0, "", false)){
				foreach($widgets as $widget){
					$widget->handler = $new_handler;
				}
			}
		}
		
	}
	
	function widget_manager_run_once_class(){
		global $CONFIG;
		
		$sql = "UPDATE " . $CONFIG->dbprefix . "entity_subtypes";
		$sql .= " SET class = 'ElggWidget'";
		$sql .= " WHERE type='object' AND subtype='widget'";
		
		return update_data($sql);
	}
?>