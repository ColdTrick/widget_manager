<?php 
	/* init file for rss widget */
	
	function widget_rss_init(){
		global $CONFIG;
		
		elgg_register_widget_type("rss", elgg_echo("widgets:rss:title"), elgg_echo("widgets:rss:description"), "groups,index,profile,dashboard", true);
		
		// extend CSS
		elgg_extend_view("css", "widgets/rss/css");
		
		// make cache directory
		if(!is_dir($CONFIG->dataroot . "/widgets/")){
			mkdir($CONFIG->dataroot . "/widgets/");
		}
		
		if(!is_dir($CONFIG->dataroot . "/widgets/rss/")){
			mkdir($CONFIG->dataroot . "/widgets/rss/");
		}
		
		// set cache settings
		define("WIDGETS_RSS_CACHE_LOCATION", $CONFIG->dataroot . "widgets/rss/");
		define("WIDGETS_RSS_CACHE_DURATION", 600);
	}
	
	function widget_rss_cron_handler($hook, $type, $params, $return_value){
		
		if($fh = opendir(WIDGETS_RSS_CACHE_LOCATION)){
			while($filename = readdir($fh)){
				if(is_file(WIDGETS_RSS_CACHE_LOCATION . $filename)){
					if(filemtime(WIDGETS_RSS_CACHE_LOCATION . $filename) < (time() - (24 * 60 * 60))){
						unlink(WIDGETS_RSS_CACHE_LOCATION . $filename);
					}
				}
			}
		}
	}
	
	// register widget init
	elgg_register_event_handler("widgets_init", "widget_manager", "widget_rss_init");
	
	// register cron for cleanup
	elgg_register_plugin_hook_handler("cron", "daily", "widget_rss_cron_handler");