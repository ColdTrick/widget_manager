<?php 

	if(isloggedin()){
		
		$context = get_input("context");
		
		$owner_guid = (int) get_input("owner_guid");
		
		if(empty($owner_guid) && ($context == "dashboard" || $context == "profile")){
			$owner_guid = get_loggedin_userid();
		}
				
		if(!empty($context) && !empty($owner_guid)){
			set_page_owner($owner_guid);
			elgg_view_title("dummy"); // triggers page_setup -> needs to be after page_owner_set 
		
			$widget_context = str_replace("default_", "", $context);
			
			$widgets = widget_manager_get_widgets($widget_context);
			$user_widgets = widget_manager_get_user_widgets($context, $owner_guid);
			
			widget_manager_sort_widgets($widgets);
			
			if(!empty($widgets)){
				$not_allowed = array();
				$widgets_count = array();
				
				if(!empty($user_widgets)){
					foreach($user_widgets as $user_widget){
						$handler = $user_widget->handler;
						
						if(!widget_manager_get_widget_setting($handler, "allow_multiple", $widget_context)){
							$not_allowed[] = $handler;
						}
						
						if(!array_key_exists($handler, $widgets_count)){
							$widgets_count[$handler] = 1;
						} else {
							$widgets_count[$handler]++;
						}
					}
				}
				
				echo "<div class='contentWrapper' id='widget_manager_widgets_select'>\n";
				
				echo "<h3 class='settings'>";
				echo "<div id='widget_manager_widgets_search'>";
				echo "<input title='" . elgg_echo("widget_manager_widgets_search") . "' type='text' value='" . elgg_echo("widget_manager_widgets_search") . "' onfocus='if($(this).val() == \"" . elgg_echo("widget_manager_widgets_search") .  "\"){ $(this).val(\"\"); }' onkeyup='widget_manager_widgets_search($(this).val());'></input>";
				echo "</div>";
				echo elgg_echo("widget_manager:widgets:lightbox:title:" . $context);
				echo "</h3>\n";
				
				foreach($widgets as $handler => $widget){
					$can_add = widget_manager_get_widget_setting($handler, "can_add", $widget_context);
					$allow_multiple = widget_manager_get_widget_setting($handler, "allow_multiple", $widget_context);
					$hide = widget_manager_get_widget_setting($handler, "hide", $widget_context);
					
					if($can_add && !$hide){
						echo "<div class='widget_manager_widgets_lightbox_wrapper'>\n";
						
						if(!in_array($handler, $not_allowed)){
							$extra_js = "";
							
							if(!$allow_multiple){
								$extra_js = "$(this).parent().parent().find(\".widget_manager_widgets_lightbox_not_present\").removeClass(\"widget_manager_widgets_lightbox_not_present\").addClass(\"widget_manager_widgets_lightbox_present\").attr(\"title\", \"1\");";
								$extra_js .= "$(this).parent().html(\"" . htmlspecialchars(elgg_echo("widget_manager:widgets:lightbox:not_allowed"), ENT_QUOTES) . "\");";
							}
							
							echo "<span class='widget_manager_widgets_lightbox_actions'>";
							echo elgg_view("input/button", array("type" => "button", 
																	"value" => elgg_echo("widget_manager:button:add"), 
																	"js" => "onclick='saveNewWidget(\"" . $handler . "\"); " . $extra_js . "'"));
							echo "</span>\n";
						} else {
							echo "<span class='widget_manager_widgets_lightbox_actions'>" . elgg_echo("widget_manager:widgets:lightbox:not_allowed") . "</span>\n";
						}
						
						if(array_key_exists($handler, $widgets_count)){
							echo "<span class='widget_manager_widgets_lightbox_present' title='" . $widgets_count[$handler] . "'></span>\n";
						} else {
							echo "<span class='widget_manager_widgets_lightbox_not_present'></span>\n";
						}
						
						echo "<span class='widget_manager_widgets_lightbox_title'>" . $widget->name . "</span>\n";
						echo "<span class='clearfloat'></span>\n";
						
						echo "<div class='widget_manager_widgets_lightbox_description'>" . $widget->description . "</div>\n";
						echo "<div class='clearfloat'></div>";
						
						echo "</div>\n";
					}
				}
				
				echo "</div>\n";
			}
		}
	}

?>