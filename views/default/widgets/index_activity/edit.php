<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->activity_count;
	if(empty($count) || !is_int($count)){
		$count = 10;
	}

?>
<div><?php echo elgg_echo("widget_manager:widgets:index_activity:activity_count"); ?></div>
<input type="text" name="params[activity_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />
