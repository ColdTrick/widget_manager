<?php 

	admin_gatekeeper();
	
	set_context("admin");
	
	$title_text = elgg_echo("widget_manager:menu:manage");
	
	// buil page elements
	$title = elgg_view_title($title_text);
		
	$body = elgg_view("widget_manager/forms/settings", array("widget_context" => "profile"));
	$body .= elgg_view("widget_manager/forms/settings", array("widget_context" => "dashboard"));
	$body .= elgg_view("widget_manager/forms/settings", array("widget_context" => "groups"));
	
	// build page
	$page_data = $title . $body;
	
	// draw page
	page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));

?>