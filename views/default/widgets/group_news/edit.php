<?php 

	$widget = $vars["entity"];

	$blog_count = (int) $widget->blog_count;
	if($blog_count < 1){
		$blog_count = 5;
	}
	$slide_timeout = (int) $widget->slide_timeout;
	if($slide_timeout < 1){
		$slide_timeout = 10; //seconds
	}
	
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
	
	echo "<div>";
	echo elgg_echo("widgets:group_news:settings:blog_count") . " ";
	echo elgg_view("input/pulldown", array("options" => array(1,2,3,4,5,6,7,8,9,10,15,20), "internalname" => "params[blog_count]", "value" => $blog_count));
	echo "</div>";	
	
	echo "<div>";
	echo elgg_echo("widgets:group_news:settings:slide_timeout") . " ";
	echo elgg_view("input/pulldown", array("options" => array(5,10,15,20,30), "internalname" => "params[slide_timeout]", "value" => $slide_timeout));
	echo "</div>";