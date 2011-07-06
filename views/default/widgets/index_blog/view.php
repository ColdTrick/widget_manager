<?php 

	$widget = $vars["entity"];
	
	// get widget settings
	$count = (int) $widget->blog_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	// backup context and set
	$old_context = get_context();
	if($widget->view_mode != "preview"){
		set_context("search");
	}
	
	$options = array(
		"type" => "object",
		"subtype" => "blog",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($blogs = elgg_list_entities($options)){
		echo $blogs;
	} else {
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widget_manager:widgets:index_blog:no_result")));
	}

	// reset context
	set_context($old_context);
?>