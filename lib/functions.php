<?php 

	function widget_manager_get_widget_setting($widget_handler, $setting, $context = null){
		$result = false;
		
		if(is_null($context)){
			$context = elgg_get_context();
		}
		
		static $widget_settings;
		if(!isset($widget_settings)){
			$widget_settings = array();
		}
		if(!isset($widget_settings[$context])){
			$widget_settings[$context] = array();
		}
		if(!isset($widget_settings[$context][$widget_handler])){
			$widget_settings[$context][$widget_handler] = array();
		}
		
		if(isset($widget_settings[$context][$widget_handler][$setting])){
			return $widget_settings[$context][$widget_handler][$setting];
		}
		
		if(widget_manager_valid_context($context)){
			if($plugin_setting = elgg_get_plugin_setting($context . "_" . $widget_handler . "_" . $setting, "widget_manager")){
				if($plugin_setting == "yes"){
					$result = true;
				}
			} elseif($setting == "can_add" || $setting == "can_remove"){
				$result = true;
			}
		}
		
		$widget_settings[$context][$widget_handler][$setting] = $result;
		
		return $result;
	}
	
	function widget_manager_set_widget_setting($widget_handler, $setting, $context, $value){
		$result = false;
		
		if(!empty($widget_handler) && !empty($setting) && widget_manager_valid_context($context)){
			$widget_setting = $context . "_" . $widget_handler . "_" . $setting;
			
			if(elgg_set_plugin_setting($widget_setting, $value, "widget_manager")){
				$result = true;
			}
		}
		
		return $result;
	}
	
	/**
	 * Register a widget title 
	 * 
	 * @param $handler
	 * @param $link
	 */
	function widget_manager_add_widget_title_link($handler, $link){
		global $CONFIG;
		
		if (!empty($handler) && !empty($link)) {
			if (isset($CONFIG->widgets) && isset($CONFIG->widgets->handlers) && isset($CONFIG->widgets->handlers[$handler])) {
				$CONFIG->widgets->handlers[$handler]->link = $link;
			}	
		}
	}
	
	/* sorts a given array of widgets alphabetically based on the widget name */
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
			if(elgg_set_plugin_setting($context . "_" . $column, $value, "widget_manager")){
				$result = true;
			}
		}
		
		return $result;
	}
	
	function widget_manager_valid_context($context){
		$result = false;
		$valid_contexts = array("profile", "dashboard", "index", "groups","admin", "default_dashboard", "default_profile");
		
		if(!empty($context) && in_array($context, $valid_contexts)){
			$result = true;
		}
		
		return $result;
	}
	
	/* checks if for a given handler a pagehandler function exists */
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
	
	/* handles widget title urls */
	function widget_manager_widget_url_handler($widget){
		$result = false;
		
		if($widget instanceof ElggWidget){
			$handler = $widget->handler;
			
			// configures some widget titles for non widgetmanager widgets
			$widget_titles = array(
								"thewire" => "[BASEURL]thewire/owner/[USERNAME]",
								"friends" => "[BASEURL]friends/[USERNAME]",
								"album_view" => "[BASEURL]photos/owned/[USERNAME]",
								"latest" => "[BASEURL]photos/mostrecent/[USERNAME]",
								"latest_photos" => "[BASEURL]photos/mostrecent/[USERNAME]",
								"messageboard" => "[BASEURL]messageboard/[USERNAME]",
								"a_users_groups" => "[BASEURL]groups/member/[USERNAME]",
								"event_calendar" => "[BASEURL]event_calendar/",
								"filerepo" => "[BASEURL]file/owner/[USERNAME]",
								"pages" => "[BASEURL]pages/owned/[USERNAME]",
								"bookmarks" => "[BASEURL]bookmarks/owner/[USERNAME]",
								"izap_videos" => "[BASEURL]izap_videos/[USERNAME]",
								"river_widget" => "[BASEURL]activity/",
								"blog" => "[BASEURL]blog/owner/[USERNAME]");
			
			if(!empty($widget->widget_manager_custom_url)){
				$link = $widget->widget_manager_custom_url;
			} elseif(array_key_exists($handler, $widget_titles)){
				$link = $widget_titles[$handler];
			} else {
				elgg_push_context($widget->context);
				$widgettypes = elgg_get_widget_types();
				elgg_pop_context();
				
				if(isset($widgettypes[$handler]->link)) {
					$link = $widgettypes[$handler]->link;
				}
			}
			
			if (!empty($link)) {
				$owner = $widget->getOwnerEntity();
				/* Let's do some basic substitutions to the link */
			
				/* [USERNAME] */
				$link = preg_replace('#\[USERNAME\]#', $owner->username, $link);
			
				/* [GUID] */
				$link = preg_replace('#\[GUID\]#', $owner->getGUID(), $link);
			
				/* [BASEURL] */
				$link = preg_replace('#\[BASEURL\]#', elgg_get_site_url(), $link);
				
				$result = $link;
			}
		}
			
		return $result;
	}
	
	/* load widget manager widgets */
	function widget_manager_load_widgets(){
		$widgets_folder = elgg_get_plugins_path() . "widget_manager/widgets";
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
