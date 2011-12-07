<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->member_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}
	
	$user_icon_options_values = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);
	
?>
<div><?php echo elgg_echo("widget_manager:widgets:index_members_online:member_count"); ?></div>
<input type="text" name="params[member_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widget_manager:widgets:index_members_online:user_icon"); ?></div>
<?php echo elgg_view("input/dropdown", array("name" => "params[user_icon]", "options_values" => $user_icon_options_values, "value" => $widget->user_icon)); ?>
