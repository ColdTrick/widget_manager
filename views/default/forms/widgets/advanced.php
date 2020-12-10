<?php
use ColdTrick\WidgetManager\WidgetsSettingsConfig;

$widget = elgg_extract('entity', $vars);
$widget_context = elgg_extract('widget_context', $vars);

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no')
];

$noyes_options = array_reverse($yesno_options, true);

$fields = [
	[
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_title'),
		'name' => 'params[widget_manager_custom_title]',
		'value' => $widget->widget_manager_custom_title,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_url'),
		'name' => 'params[widget_manager_custom_url]',
		'value' => $widget->widget_manager_custom_url,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_more_title'),
		'name' => 'params[widget_manager_custom_more_title]',
		'value' => $widget->widget_manager_custom_more_title,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_more_url'),
		'name' => 'params[widget_manager_custom_more_url]',
		'value' => $widget->widget_manager_custom_more_url,
	],
];

$advanced_context = elgg_trigger_plugin_hook('advanced_context', 'widget_manager', ['entity' => $widget], ['index']);

if (is_array($advanced_context) && in_array($widget_context, $advanced_context)) {
	
	$fields[] = [
		'#type' => 'checkbox',
		'#label' => elgg_echo('widget_manager:widgets:edit:hide_header'),
		'name' => 'params[widget_manager_hide_header]',
		'checked' => $widget->widget_manager_hide_header === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	];
	$fields[] = [
		'#type' => 'checkbox',
		'#label' => elgg_echo('widget_manager:widgets:edit:disable_widget_content_style'),
		'name' => 'params[widget_manager_disable_widget_content_style]',
		'checked' => $widget->widget_manager_disable_widget_content_style === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	];
	$fields[] = [
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_class'),
		'name' => 'params[widget_manager_custom_class]',
		'value' => $widget->widget_manager_custom_class,
	];
	$fields[] = [
		'#type' => 'checkbox',
		'#label' => elgg_echo('widget_manager:widgets:edit:collapse_disable'),
		'name' => 'params[widget_manager_collapse_disable]',
		'checked' => $widget->widget_manager_collapse_disable === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	];
	$fields[] = [
		'#type' => 'dropdown',
		'#label' => elgg_echo('widget_manager:widgets:edit:collapse_state'),
		'name' => 'params[widget_manager_collapse_state]',
		'value' => $widget->widget_manager_collapse_state,
		'options_values' => [
			'0' => elgg_echo('status:open'),
			'closed' => elgg_echo('status:closed'),
		],
	];
	
	if ((bool) elgg_get_plugin_setting('lazy_loading_enabled', 'widget_manager') && !WidgetsSettingsConfig::instance()->getSetting($widget->handler, 'always_lazy_load', $widget->context)) {
		$fields[] = [
			'#type' => 'checkbox',
			'#label' => elgg_echo('widget_manager:widgets:edit:lazy_load_content'),
			'name' => 'params[widget_manager_lazy_load_content]',
			'checked' => !empty($widget->widget_manager_lazy_load_content),
			'switch' => true,
			'default' => 0,
			'value' => 1,
		];
	}
}

echo elgg_view('input/fieldset', ['fields' => $fields]);
