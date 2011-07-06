<?php 

	$widgets = $vars["widgets"];
	$widget_context = $vars["widget_context"];
	$configured_widgets = $vars["configured_widgets"];
	
	$gallery_widgets = "";
	
	if(!empty($widgets)){
		foreach($widgets as $handler => $widget){
			$gallery_widgets .= elgg_view("widget_manager/widget", array("handler" => $handler, "widget" => $widget));
		}
	}
	
	$filtered_widgets = array(
		"fixed_widgets_left" => "",
		"fixed_widgets_middle" => "",
		"fixed_widgets_right" => "",
		"free_widgets_left" => "",
		"free_widgets_middle" => "",
		"free_widgets_right" => "",
	);
	
	$showing_widgets = array(
		"fixed_widgets_left" => "",
		"fixed_widgets_middle" => "",
		"fixed_widgets_right" => "",
		"free_widgets_left" => "",
		"free_widgets_middle" => "",
		"free_widgets_right" => "",
	);
	
	foreach($configured_widgets as $column => $positions){
		
		foreach($positions as $pos => $handlers){
			
			foreach($handlers as $handler){
				if(array_key_exists($handler, $widgets)){
					if(!empty($filtered_widgets[$pos . "_widgets_" . $column])){
						$filtered_widgets[$pos . "_widgets_" . $column] .= "," . $handler;
					} else {
						$filtered_widgets[$pos . "_widgets_" . $column] = $handler;
					}
					
					$showing_widgets[$pos . "_widgets_" . $column] .= elgg_view("widget_manager/widget", array("handler" => $handler, "widget" => $widgets[$handler]));
				}
			}
		}
	}

?>
<div class="widget_manager_manage" id="widget_manager_manage_placement">
	<div id="widget_manager_manage_placement_widgets">
		<h3 class="settings"><span class='widget_manager_more_info' id='widget_manager_more_info_fixed'></span><?php echo elgg_echo("widget_manager:manage:form:placement:fixed:title"); ?></h3>
		
		<input type="hidden" name="<?php echo $widget_context; ?>_fixed_widgets_left" value="<?php echo $filtered_widgets["fixed_widgets_left"]; ?>" />
		<input type="hidden" name="<?php echo $widget_context; ?>_fixed_widgets_middle" value="<?php echo $filtered_widgets["fixed_widgets_middle"]; ?>" />
		<input type="hidden" name="<?php echo $widget_context; ?>_fixed_widgets_right" value="<?php echo $filtered_widgets["fixed_widgets_right"]; ?>" />
		
		<div id="widget_manager_manage_placement_fixed_container">
			<div id="<?php echo $widget_context;?>_fixed_widgets_left" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:leftcolumn"); ?></div>
				<?php echo $showing_widgets["fixed_widgets_left"]; ?>
			</div>
			<div id="<?php echo $widget_context;?>_fixed_widgets_middle" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:middlecolumn"); ?></div>
				<?php echo $showing_widgets["fixed_widgets_middle"]; ?>
			</div>
			<div id="<?php echo $widget_context;?>_fixed_widgets_right" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:rightcolumn"); ?></div>
				<?php echo $showing_widgets["fixed_widgets_right"]; ?>
			</div>
			<div class="clearfloat"></div>
		</div>
		
		<h3 class="settings"><span class='widget_manager_more_info' id='widget_manager_more_info_free'></span><?php echo elgg_echo("widget_manager:manage:form:placement:free:title"); ?></h3>
		
		<input type="hidden" name="<?php echo $widget_context; ?>_free_widgets_left" value="<?php echo $filtered_widgets["free_widgets_left"]; ?>" />
		<input type="hidden" name="<?php echo $widget_context; ?>_free_widgets_middle" value="<?php echo $filtered_widgets["free_widgets_middle"]; ?>" />
		<input type="hidden" name="<?php echo $widget_context; ?>_free_widgets_right" value="<?php echo $filtered_widgets["free_widgets_right"]; ?>" />
		
		<div id="widget_manager_manage_placement_free_container">
			<div id="<?php echo $widget_context;?>_free_widgets_left" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:leftcolumn"); ?></div>
				<?php echo $showing_widgets["free_widgets_left"]; ?>
			</div>
			<div id="<?php echo $widget_context;?>_free_widgets_middle" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:middlecolumn"); ?></div>
				<?php echo $showing_widgets["free_widgets_middle"]; ?>
			</div>
			<div id="<?php echo $widget_context;?>_free_widgets_right" class="widget_manager_placement_widget_column">
				<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:rightcolumn"); ?></div>
				<?php echo $showing_widgets["free_widgets_right"]; ?>
			</div>
			<div class="clearfloat"></div>
		</div>
	</div>
	
	<div id="widget_manager_manage_placement_gallery">
		<div class="widget_manager_placement_widget_column_title"><?php echo elgg_echo("widgets:gallery"); ?></div>
		<div id="widget_manager_manage_placement_gallery_widgets">
			<?php echo $gallery_widgets; ?>
		</div>
	</div>
	<div class="clearfloat"></div>
	
	<div>
		<input type="checkbox" name="<?php echo $widget_context; ?>_enforce_free" value="yes" />
		&nbsp;<?php echo elgg_echo("widget_manager:form:enforce_presence"); ?>
		<br />
	</div>
</div>

<div class="widget_manager_more_info_tooltip_text" id="text_widget_manager_more_info_fixed"><?php echo elgg_echo("widget_manager:tooltips:fixed");?></div>
<div class="widget_manager_more_info_tooltip_text" id="text_widget_manager_more_info_free"><?php echo elgg_echo("widget_manager:tooltips:free");?></div>