<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->group_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}
	
	$featured_options_values = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
?>
<div><?php echo elgg_echo("widget_manager:widgets:index_groups:group_count"); ?></div>
<input type="text" name="params[group_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widget_manager:widgets:index_groups:featured"); ?></div>
<?php echo elgg_view("input/dropdown", array("name" => "params[featured]", "options_values" => $featured_options_values, "value" => $widget->featured)); ?>
