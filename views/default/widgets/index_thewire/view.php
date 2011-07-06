<?php 

	$widget = $vars["entity"];
	
	// backup context and set
	$old_context = get_context();
	set_context("search");
	
	// get widget settings
	$count = (int) $widget->wire_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "thewire",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false,
		"view_type_toggle" => false
	);
	
	if($wires = elgg_list_entities($options)){
		echo $wires;
	} else {
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widget_manager:widgets:index_thewire:no_result")));
	}

	// reset context
	set_context($old_context);
?>