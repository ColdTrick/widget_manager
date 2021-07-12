<?php

$widgets_config = get_input('widgets_config');
if (empty($widgets_config) || !is_array($widgets_config)) {
	return elgg_error_response();
}

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

$setting = elgg_get_plugin_setting('widgets_config', 'widget_manager', []);
if (is_string($setting)) {
	$setting = json_decode($setting, true);
}

foreach ($widgets_config as $widget_id => $widget_config) {
	$configured_widget = elgg_extract($widget_id, $configured_widgets);
	if (empty($configured_widget)) {
		unset($setting[$widget_id]);
		continue;
	}

	// only store if different
	if (isset($widget_config['multiple'])) {
		if ((bool) $widget_config['multiple'] == (bool) $configured_widget->originals['multiple']) {
			unset($setting[$widget_id]['multiple']);
		} else {
			$setting[$widget_id]['multiple'] = 1;
		}
	}
	
	if (isset($widget_config['contexts'])) {
		foreach ($widget_config['contexts'] as $context => $context_config) {
			foreach ($context_config as $option => $value) {
				$setting[$widget_id]['contexts'][$context][$option] = $value;
			}
		}
	}
}

$plugin = elgg_get_plugin_from_id('widget_manager');
$plugin->setSetting('widgets_config', json_encode($setting));

return elgg_ok_response('', elgg_echo('widget_manager:action:manage:success'));
