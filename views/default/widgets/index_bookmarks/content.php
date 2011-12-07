<?php 

	$widget = $vars["entity"];
	
	elgg_push_context("search");
	
	// get widget settings
	$count = (int) $widget->bookmark_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "bookmarks",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($bookmarks = elgg_list_entities($options)){
		echo $bookmarks;
	} else {
		echo elgg_echo("widget_manager:widgets:index_bookmarks:no_result");
	}

	elgg_pop_context();