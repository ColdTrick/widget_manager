<?php 

	$widget_context = $vars["widget_context"];
	
	// get widgets
	$widgets = widget_manager_get_widgets($widget_context);
	
	if(!empty($widgets)){
		$list .= "<table class='widget_manager_table_layout'>\n";
		
		$list .= "<tr>\n";
		$list .= "<th class='widget_manager_table_title'>&nbsp;</th>\n";
		$list .= "<th>" . elgg_echo("widget_manager:forms:settings:can_add") . "</th>\n";
		$list .= "<th>" . elgg_echo("widget_manager:forms:settings:can_remove") . "</th>\n";
		$list .= "<th>" . elgg_echo("widget_manager:forms:settings:allow_multiple") . "</th>\n";
		$list .= "<th>" . elgg_echo("widget_manager:forms:settings:hide") . "</th>\n";
		$list .= "</tr>\n";
		
		foreach($widgets as $handler => $widget){
			$check_add = "";
			$check_remove = "";
			$check_multiple = "";
			$check_hide = "";
			
			if(widget_manager_get_widget_setting($handler, "can_add", $widget_context)){
				$check_add = "checked='checked'";
			}
			
			if(widget_manager_get_widget_setting($handler, "can_remove", $widget_context)){
				$check_remove = "checked='checked'";
			}
			
			if(widget_manager_get_widget_setting($handler, "allow_multiple", $widget_context)){
				$check_multiple = "checked='checked'";
			}
			
			if(widget_manager_get_widget_setting($handler, "hide", $widget_context)){
				$check_hide = "checked='checked'";
			}
			
			$list .= "<tr>\n";
			$list .= "<td class='widget_manager_table_title'><span title='" . $widget->description . "'>" . $widget->name . "</span></td>\n";
			$list .= "<td><input type='checkbox' name='" . $widget_context . "_" . $handler . "_can_add' value='yes' " . $check_add . " /></td>\n";
			$list .= "<td><input type='checkbox' name='" . $widget_context . "_" . $handler . "_can_remove' value='yes' " . $check_remove . "/></td>\n";
			$list .= "<td><input type='checkbox' name='" . $widget_context . "_" . $handler . "_allow_multiple' value='yes' " . $check_multiple . "/></td>\n";
			$list .= "<td><input type='checkbox' name='" . $widget_context . "_" . $handler . "_hide' value='yes' " . $check_hide . "/></td>\n";
			$list .= "</tr>\n";
		}
		
		$list .= "</table>\n";
		
		$form_body .= elgg_view("input/hidden", array("internalname" => "widget_context", "value" => $widget_context));
		$form_body .= $list;
		
		$form_body .= "<div>\n";
		$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
		$form_body .= "</div>\n";
		
		
		$body = "<h3 class='settings'>" . elgg_echo("widget_manager:" . $widget_context . ":title") . "</h3>";
		$body .= elgg_view("input/form", array("body" => $form_body,
												"action" => $vars["url"] . "action/widget_manager/manage",
												"internalid" => "widget_manager_manage_form"
		));	
		
	} else {
		$body = elgg_echo("widget_manager:forms:settings:no_widgets");
	}

?>
<div class="contentWrapper">
	<?php echo $body;?>
</div>