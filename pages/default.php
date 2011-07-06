<?php 

	admin_gatekeeper();
	
	global $CONFIG;
	
	// get widget context
	$widget_context = get_input("widget_context");
	
	set_context("default_" . $widget_context);
	
	set_page_owner($CONFIG->site_guid); // default widgets are owned by the site
	
	$title_text = elgg_echo("widget_manager:" . $widget_context . ":title");
	
	// build page elements
	$body = elgg_view_title($title_text);
	$body .= elgg_view("widget_manager/default", array("context" => $widget_context));
	
	// draw page
	page_draw($title_text, elgg_view_layout("widgets", $body));

?>