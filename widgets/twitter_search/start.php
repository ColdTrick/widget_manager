<?php 
/* init file for twitter_search widget */

function widget_twitter_search_init(){
	elgg_register_widget_type("twitter_search", elgg_echo("widgets:twitter_search:name"), elgg_echo("widgets:twitter_search:description"), "profile,dashboard,index,groups", true);
	
	elgg_register_plugin_hook_handler("widget_settings", "twitter_search", "widget_twitter_search_settings_save_hook");
}

function widget_twitter_search_settings_save_hook($hook_name, $entity_type, $return_value, $params) {
	$widget = elgg_extract("widget", $params);
	if ($widget && ($entity_type == "twitter_search")) {
		$embed_code = elgg_extract("embed_code", get_input("params", array(), false)); // do not strip code
		
		$widget_id = false;
		
		if ($embed_code) {
			
			$start_pos = strpos($embed_code, 'data-widget-id="') + strlen('data-widget-id="');
			$end_pos = strpos($embed_code, '"', $start_pos );
			
			$widget_id = filter_tags(substr($embed_code, $start_pos, $end_pos - $start_pos));
			
			if ($widget_id) {
				$widget->widget_id = $widget_id;
			} else {
				register_error(elgg_echo("widgets:twitter_search:embed_code:error"));
			}
		}
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_twitter_search_init");
