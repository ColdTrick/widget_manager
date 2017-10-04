<?php
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
		'#type' => 'dropdown',
		'#label' => elgg_echo('widget_manager:widgets:edit:hide_header'),
		'name' => 'params[widget_manager_hide_header]',
		'value' => $widget->widget_manager_hide_header,
		'options_values' => $noyes_options,
	];
	$fields[] = [
		'#type' => 'dropdown',
		'#label' => elgg_echo('widget_manager:widgets:edit:disable_widget_content_style'),
		'name' => 'params[widget_manager_disable_widget_content_style]',
		'value' => $widget->widget_manager_disable_widget_content_style,
		'options_values' => $noyes_options,
	];
	$fields[] = [
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:custom_class'),
		'name' => 'params[widget_manager_custom_class]',
		'value' => $widget->widget_manager_custom_class,
	];
	$fields[] = [
		'#type' => 'text',
		'#label' => elgg_echo('widget_manager:widgets:edit:fixed_height'),
		'name' => 'params[widget_manager_fixed_height]',
		'value' => $widget->widget_manager_fixed_height,
	];
	$fields[] = [
		'#type' => 'dropdown',
		'#label' => elgg_echo('widget_manager:widgets:edit:collapse_disable'),
		'name' => 'params[widget_manager_collapse_disable]',
		'value' => $widget->widget_manager_collapse_disable,
		'options_values' => $noyes_options,
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
}

$fieldset = elgg_view('input/fieldset', ['fields' => $fields]);

echo elgg_view_module('info', elgg_echo('widget_manager:widgets:edit:advanced'), $fieldset, [
	'class' => 'hidden',
	'id' => "widget-manager-widget-edit-advanced-{$widget->getGUID()}",
]);

echo elgg_view('output/url', [
	'rel' => 'toggle',
	'href' => "#widget-manager-widget-edit-advanced-{$widget->getGUID()}",
	'class' => 'elgg-button elgg-button-action float-alt',
	'text' => elgg_echo('widget_manager:widgets:edit:advanced'),
]);
