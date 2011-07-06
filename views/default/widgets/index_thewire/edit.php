<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->wire_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

?>
<div><?php echo elgg_echo("widget_manager:widgets:index_thewire:wire_count"); ?></div>
<input type="text" name="params[wire_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />