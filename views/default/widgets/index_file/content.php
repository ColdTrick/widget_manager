<?php 

	$widget = $vars["entity"];
	
	elgg_push_context("search");
	
	// get widget settings
	$count = (int) $widget->file_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "file",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($files = elgg_list_entities($options)){
		echo $files;
	} else {
		echo elgg_echo("widget_manager:widgets:index_file:no_result");
	}

	elgg_pop_context();