<?php 

	$handler = $vars["handler"];
	$widget = $vars["widget"];
	
	
?>
<div class="widget_manager_widget_wrapper">
	<input type="hidden" name="widget_handler[]" value="<?php echo $handler; ?>" />
	<span class="widget_manager_widget_actions">
		<span title="<?php echo $widget->description; ?>" class="widget_manager_widget_description"></span>
		<span class="widget_manager_widget_move"></span>
		
	</span>
	<span class="widget_manager_widget_title"><?php echo $widget->name; ?></span>
	<span class="clearfloat"></span>
</div>