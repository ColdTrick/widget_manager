<?php 

	action_gatekeeper();
	admin_gatekeeper();
	
	$widget_context = get_input("widget_context");
	
	if(!empty($widget_context)){
		$error_count = 0;
		
		if($widgets = widget_manager_get_widgets($widget_context)){
			$toggle_settings = array("can_add", "can_remove", "allow_multiple", "hide");
			
			foreach($widgets as $handler => $widget){
				
				foreach($toggle_settings as $setting){
					$input_name = $widget_context . "_" . $handler . "_" . $setting;
					$value = get_input($input_name, "no");
					
					if(!widget_manager_set_widget_setting($handler, $setting, $widget_context, $value)){
						$error_count++;
						register_error(sprintf(elgg_echo("widget_manager:action:manage:error:save_setting"), $setting, $widget->name));
					}
				}
			}
		}
		
//		$columns = array(
//			"fixed_widgets_left",
//			"fixed_widgets_middle",
//			"fixed_widgets_right",
//			"free_widgets_left",
//			"free_widgets_middle",
//			"free_widgets_right",
//		);
//		
//		$enforce_fixed = false;
//		$old_configuration = widget_manager_get_configured_widgets($widget_context);
//		
//		foreach($columns as $column){
//			$handlers = str_replace("::", ",", str_replace("::0", "", get_input($widget_context . "_" . $column)));
//			
//			list($pos, $dummy, $col) = explode("_", $column);
//			
//			if($old_configuration[$col][$pos] != $handlers){
//				if(stristr($column, "fixed")){
//					$enforce_fixed = true;
//				}
//				
//				if(!widget_manager_set_configured_widgets($widget_context, $column, $handlers)){
//					$error_count++;
//					register_error(sprintf(elgg_echo("widget_manager:action:manage:error:save_placement"), $column));
//				}
//			}
//		}
//		
//		if($enforce_fixed){
//			set_plugin_setting($widget_context . "_enforce_fixed", time(), "widget_manager");
//		}
//		
//		if(get_input($widget_context . "_enforce_free") == "yes"){
//			set_plugin_setting($widget_context . "_enforce_free", time(), "widget_manager");
//		}
		
		if($error_count == 0){
			system_message(elgg_echo("widget_manager:action:manage:success"));
		}
	} else {
		register_error(elgg_echo("widget_manager:action:manage:error:context"));
	}
	
	forward($_SERVER["HTTP_REFERER"]);

?>