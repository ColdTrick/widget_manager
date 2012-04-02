<?php
$widget = $vars["entity"];

$discussion_count = $widget->discussion_count;
if(empty($discussion_count )){
	$discussion_count = 5;
}

if(elgg_in_context("dashboard")){
	
	$noyes_options = array(
			"no" => elgg_echo("option:no"),
			"yes" => elgg_echo("option:yes")		
	);
	?>
	<div>
	<?php 
		echo "<label>" . elgg_echo("widgets:discussion:settings:group_only") . "</label>";
		echo elgg_view("input/dropdown", array("name" => "params[group_only]", "value" => $widget->group_only, "options_values" => $noyes_options));
	?>
	</div>
	<?php 
}
?>
<div>
<?php
	echo "<label>" . elgg_echo("widget:numbertodisplay") . "</label>";
	echo elgg_view("input/dropdown", array("name" => "params[discussion_count]", "value" => $discussion_count, "options" =>  range(1, 10)));
?>
</div>