<?php 

	$widget = $vars["entity"];
	
	elgg_push_context("search");
	
	// get widget settings
	$count = (int) $widget->pages_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "page_top",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($pages = elgg_list_entities($options)){
		echo $pages;
	} else {
		echo elgg_echo("widget_manager:widgets:index_pages:no_result");
	}

	elgg_pop_context();