<?php
$contexts = ['index'];

// Use contexts defined for default widgets
$list = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, []);
foreach ($list as $context_opts) {
	$contexts[] = $context_opts['widget_context'];
}

$configured_widgets = [];
foreach ($contexts as $context) {
	$configured_widgets += elgg_get_widget_types($context);
}

// make sure widgets are manageable, even if they are not configured for any context
elgg_register_plugin_hook_handler('handlers', 'widgets', '\ColdTrick\WidgetManager\Widgets::addManageWidgetsContext', 10000);
$configured_widgets += elgg_get_widget_types('manage_widgets');
elgg_unregister_plugin_hook_handler('handlers', 'widgets', '\ColdTrick\WidgetManager\Widgets::addManageWidgetsContext');

echo elgg_view_form('widget_manager/manage_widgets', [], ['widgets' => $configured_widgets, 'contexts' => $contexts]);
