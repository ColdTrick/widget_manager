<?php
		 
	$group = $vars["entity"];

	if($group->widget_manager_enable == "yes"){
		$area1widgets = get_widgets($group->getGUID(), elgg_get_context(), 1);
		$area2widgets = get_widgets($group->getGUID(), elgg_get_context(), 2);
		$area3widgets = get_widgets($group->getGUID(), elgg_get_context(), 3);
	}
	$context = elgg_get_context();
	
	$widget_manager_enable = false;
	if($group->widget_manager_enable == "yes"){
		$widget_manager_enable = true;
	}
	
	echo "<div class=\"clearfloat\"></div>";
	echo "<div id='widget_table' class='group_widgets_layout'>";
	if ($group->canEdit() && $widget_manager_enable){
		?>
		<form action="<?php echo $vars['url']; ?>action/widgets/reorder" method="post" id="widget_manager_groups_reorder_form">
			<textarea name="debugField1" id="debugField1"></textarea>
			<textarea name="debugField2" id="debugField2"></textarea>
			<textarea name="debugField3" id="debugField3"></textarea>
			
			<input type="hidden" name="context" value="<?php echo $context; ?>" />
			<input type="hidden" name="owner" value="<?php echo $group->getGUID(); ?>" />
			
			<?php echo elgg_view("input/securitytoken"); ?>
		</form>

		<div id="toggle_customise_edit_panel">
			<a href="<?php echo $vars["url"]; ?>widget_manager/widgets/lightbox?context=<?php echo $context; ?>&owner_guid=<?php echo $group->getGUID(); ?>" class="toggle_customise_edit_panel_override">
				<?php echo elgg_echo("widget_manager:add");?>
			</a>
		</div>
		<?php
	}
	
	echo "<div id=\"fullcolumn\">";
	if($widget_manager_enable){
		echo "<div id=\"widgets_left\">";
		if (is_array($area1widgets) && sizeof($area1widgets) > 0){
			foreach($area1widgets as $widget) {
				echo elgg_view_entity($widget);
			}
		}	
		echo "</div>";
	} else {
		echo elgg_view("groups/forum_latest",array('entity' => $vars['entity']));
	}
	echo "</div>";
	 
	//right column
	echo "<div id=\"right_column\">";
	if($widget_manager_enable){
		echo "<div id=\"widgets_right\">";
		if (is_array($area3widgets) && sizeof($area3widgets) > 0){
			foreach($area3widgets as $widget) {
				echo elgg_view_entity($widget);
			}
		}

		echo "</div>";
	} else {
		 echo elgg_view("groups/right_column",array('entity' => $vars['entity']));
	}
	echo "</div>";
	 
	//left column
	echo "<div id=\"left_column\">";
	if($widget_manager_enable){
		echo "<div id=\"widgets_middle\">";
		if (is_array($area2widgets) && sizeof($area2widgets) > 0){
			foreach($area2widgets as $widget) {
				echo elgg_view_entity($widget);
			}
		}
	
		echo "</div>";	 
	} else {
		 echo elgg_view("groups/left_column",array('entity' => $vars['entity']));
	}
	echo "</div>";
	echo "<div class=\"clearfloat\"></div>";	 
	echo "</div>";
