<?php
$settings = "";
$tabs = array();

$selected_context = get_input("widget_context", "profile");

$contexts = array();

// Use contexts defined for default widgets
$list = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());
foreach ($list as $context_opts) {
	$contexts[] = $context_opts['widget_context'];
}

foreach ($contexts as $context) {
	$selected = false;
	if ($selected_context === $context) {
		$selected = true;
	}
	$tabs_options = array(
			"title" => elgg_echo($context),
			"selected" => $selected,
			"url" => "admin/widgets/manage?widget_context=" . $context
		);

	$tabs[] = $tabs_options;
}

$body = elgg_view("navigation/tabs", array("tabs" => $tabs));
$body .= elgg_view("widget_manager/forms/settings", array("widget_context" => $selected_context));

echo $body;
