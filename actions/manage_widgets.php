<?php
	
$widget_context = get_input('widget_context');

if (empty($widget_context)) {
	register_error(elgg_echo('widget_manager:action:manage:error:context'));
	forward(REFERER);
}

$widgets = elgg_get_widget_types($widget_context);
if (empty($widgets)) {
	system_message(elgg_echo('widget_manager:action:manage:success'));
	forward(REFERER);
}

$error_count = 0;
$toggle_settings = ['can_add', 'hide'];

foreach ($widgets as $handler => $widget) {
	foreach ($toggle_settings as $setting) {
		$value = get_input("{$widget_context}_{$handler}_{$setting}");
		if ($value !== 'yes') {
			$value == 'no';
		}
		
		if (!widget_manager_set_widget_setting($handler, $setting, $widget_context, $value)) {
			$error_count++;
			register_error(elgg_echo('widget_manager:action:manage:error:save_setting', [$setting, $widget->name]));
		}
	}
}

elgg_get_system_cache()->delete('widget_manager_widget_settings');

if ($error_count == 0) {
	system_message(elgg_echo('widget_manager:action:manage:success'));
}

forward(REFERER);
