<?php 

	$widget = $vars["entity"];
	
	// backup context and set
	$old_context = get_context();
	set_context("search");
	
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
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widget_manager:widgets:index_bookmarks:no_result")));
	}

	// reset context
	set_context($old_context);
?>