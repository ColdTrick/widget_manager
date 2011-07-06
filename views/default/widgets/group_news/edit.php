<?php 

	$widget = $vars["entity"];

	$options_values = array("" => elgg_echo("widgets:group_news:settings:no_project"));
	$all_groups = elgg_get_entities(array("type" => "group", "limit" => false));
	foreach($all_groups as $group){
		$options_values[$group->getGUID()] = $group->name;	
	}
	
	for($i = 1;$i < 6; $i++){
		$metadata_name = "project_" . $i;
		echo $metadata_name;
		echo $widget->get($metadata_name);
		echo "<div>";
		echo elgg_echo("widgets:group_news:settings:project") . " ";
		echo elgg_view("input/pulldown", array("options_values" => $options_values, "internalname" => "params[" . $metadata_name . "]", "value" => $widget->$metadata_name));
		echo "</div>";	
	}
?>