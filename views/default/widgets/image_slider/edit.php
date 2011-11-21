<?php 

	$widget = $vars["entity"];
	
	$max_slider_options = 5;
	
	$seconds_per_slide = (int) $widget->seconds_per_slide;
	if(empty($seconds_per_slide) || !is_int($seconds_per_slide)){
		$seconds_per_slide = 10;
	}
	
	$slider_height = (int) $widget->slider_height;
	if(empty($slider_height) || !is_int($slider_height)){
		$slider_height = 300;
	}
	
	$overlay_color = $widget->overlay_color;
	if(empty($overlay_color)){
		$overlay_color = "4690D6";
	}
	
	$direction_options_values = array(
		"top" => elgg_echo("widget_manager:widgets:image_slider:direction:top"),
		"right" => elgg_echo("widget_manager:widgets:image_slider:direction:right"),
		"bottom" => elgg_echo("widget_manager:widgets:image_slider:direction:bottom"),
		"left" => elgg_echo("widget_manager:widgets:image_slider:direction:left"),
	);
	
	$slider_type_options = array(
			"s3slider" => elgg_echo("widget_manager:widgets:image_slider:slider_type:s3slider"),
			"flexslider" => elgg_echo("widget_manager:widgets:image_slider:slider_type:flexslider"),
		);
	
for($i = 1; $i <= $max_slider_options; $i++){
		
	$direction = $widget->get("slider_" . $i . "_direction");
	if(empty($direction)){
		$direction = "top";
	}
	?>
	<div class='image_slider_settings'>
		<h3><?php echo elgg_echo("widget_manager:widgets:image_slider:title"); ?> - <?php echo $i; ?></h3>
		<span>
			<div><?php echo elgg_echo("widget_manager:widgets:image_slider:label:url"); ?></div>
			<?php echo elgg_view("input/text", array("internalname" => "params[slider_" . $i . "_url]", "value" => $widget->get("slider_" . $i . "_url"))); ?>
			
			<div><?php echo elgg_echo("widget_manager:widgets:image_slider:label:text"); ?></div>
			<?php echo elgg_view("input/text", array("internalname" => "params[slider_" . $i . "_text]", "value" => $widget->get("slider_" . $i . "_text"))); ?>
			
			<div><?php echo elgg_echo("widget_manager:widgets:image_slider:label:link"); ?></div>
			<?php echo elgg_view("input/text", array("internalname" => "params[slider_" . $i . "_link]", "value" => $widget->get("slider_" . $i . "_link"))); ?>
			
			<div><?php echo elgg_echo("widget_manager:widgets:image_slider:label:direction"); ?></div>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[slider_" . $i . "_direction]", "options_values" => $direction_options_values, "value" => $direction)); ?>
			
		</span>
	</div>
	<?php 
	
}
	
?>
<hr />
<div><?php echo elgg_echo("widget_manager:widgets:image_slider:slider_type"); ?></div>
<?php echo elgg_view("input/pulldown", array("internalname" => "params[slider_type]", "value" => $widget->slider_type, "options_values" => $slider_type_options));?>

<div><?php echo elgg_echo("widget_manager:widgets:image_slider:seconds_per_slide"); ?></div>
<input type="text" name="params[seconds_per_slide]" value="<?php echo elgg_view("output/text", array("value" => $seconds_per_slide)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widget_manager:widgets:image_slider:slider_height"); ?></div>
<input type="text" name="params[slider_height]" value="<?php echo elgg_view("output/text", array("value" => $slider_height)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widget_manager:widgets:image_slider:overlay_color"); ?></div>
<input type="text" name="params[overlay_color]" value="<?php echo elgg_view("output/text", array("value" => $overlay_color)); ?>" size="6" maxlength="6" />

<script type="text/javascript">
	$(document).ready(function() {

		$(".image_slider_settings>h3").live("click", function(){
			$(this).next().toggle();
		});
	});
</script>
