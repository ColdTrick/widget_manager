<?php

$contexts = ['index'];

// Use contexts defined for default widgets
$list = elgg_trigger_event_results('get_list', 'default_widgets', [], []);
foreach ($list as $context_opts) {
	$contexts[] = $context_opts['widget_context'];
}

natcasesort($contexts);

$configured_widgets = [];
foreach ($contexts as $context) {
	$configured_widgets += elgg_get_widget_types($context);
}

// make sure widgets are manageable, even if they are not configured for any context
elgg_register_event_handler('handlers', 'widgets', '\ColdTrick\WidgetManager\Widgets::addManageWidgetsContext', 10000);
$configured_widgets += elgg_get_widget_types('manage_widgets');
elgg_unregister_event_handler('handlers', 'widgets', '\ColdTrick\WidgetManager\Widgets::addManageWidgetsContext');

if (empty($configured_widgets)) {
	echo elgg_echo('widget_manager:forms:manage_widgets:no_widgets');
	return;
}

elgg_import_esm('admin/widgets/manage');

$widget_output = [];

foreach ($configured_widgets as $widget_definition) {
	$key = $widget_definition->name . '_' . $widget_definition->id;
	$widget_output[$key] = elgg_view('forms/widget_manager/manage_widgets/widget', [
		'widget' => $widget_definition,
		'contexts' => $contexts,
	]);
}

ksort($widget_output);

echo implode('', $widget_output);
