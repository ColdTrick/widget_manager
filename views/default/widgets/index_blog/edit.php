<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->blog_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}
	
	$view_mode_options_values = array(
		"list" => elgg_echo("widget_manager:widgets:index_blog:view_mode:list"),
		"preview" => elgg_echo("widget_manager:widgets:index_blog:view_mode:preview")
	);

?>
<div><?php echo elgg_echo("widget_manager:widgets:index_blog:blog_count"); ?></div>
<input type="text" name="params[blog_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widget_manager:widgets:index_blog:view_mode"); ?></div>
<?php echo elgg_view("input/pulldown", array("internalname" => "params[view_mode]", "options_values" => $view_mode_options_values, "value" => $widget->view_mode)); ?>