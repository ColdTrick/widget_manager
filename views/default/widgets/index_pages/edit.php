<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->pages_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

?>
<div><?php echo elgg_echo("widget_manager:widgets:index_pages:pages_count"); ?></div>
<input type="text" name="params[pages_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />