<?php
$settings = '';
$tabs = [];

$selected_context = get_input('widget_context', 'profile');

$contexts = [];

// Use contexts defined for default widgets
$list = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, []);
foreach ($list as $context_opts) {
	$contexts[] = $context_opts['widget_context'];
}

foreach ($contexts as $context) {
	$tabs[] = [
		'title' => elgg_echo($context),
		'selected' => ($selected_context === $context),
		'url' => 'admin/widgets/manage?widget_context=' . $context,
	];
}

$body = elgg_view('navigation/tabs', ['tabs' => $tabs]);
$body .= elgg_view_form('widget_manager/manage_widgets', [], ['widget_context' => $selected_context]);

echo $body;
