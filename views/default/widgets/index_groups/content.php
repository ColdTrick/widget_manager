<?php 

	$widget = $vars["entity"];
	
	// get widget settings
	$count = (int) $widget->group_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	elgg_push_context("search");
	
	$options = array(
		"type" => "group",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($widget->featured == "yes"){
		$options["metadata_name"] = "featured_group";
		$options["metadata_value"] = "yes";
	}
	
	if($groups = elgg_list_entities_from_metadata($options)){
		echo $groups;
	} else {
		echo elgg_echo("widget_manager:widgets:index_groups:no_result");
	}

	elgg_pop_context();