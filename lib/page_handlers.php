<?php

	function widget_manager_multi_dashboard_page_handler($page){
		$result = false;
		
		switch($page[0]){
			case "edit":
				$result = true;
				
				if(!empty($page[1])){
					set_input("guid", $page[1]);
				}
				
				include(dirname(dirname(__FILE__)) . "/pages/multi_dashboard/edit.php");
				break;
		}
		
		return $result;
	}
	
	function widget_manager_extra_contexts_page_handler($page, $handler) {
		$result = false;
		
		$extra_contexts = elgg_get_plugin_setting("extra_contexts", "widget_manager");
		if ($extra_contexts) {
			$contexts = string_to_tag_array($extra_contexts);
			if ($contexts) {
				if(in_array($handler, $contexts)) {
					$result = true;
					
					// make nice lightbox popup title
					add_translation(get_current_language(), array("widget_manager:widgets:lightbox:title:" . strtolower($handler) => $handler));
					
					include(dirname(dirname(__FILE__)) . "/pages/extra_contexts.php");
				}
			}
		}
		
		return $result;
	}