<?php

$plugin = elgg_extract('entity', $vars);

$index_settings = '';

$index_settings .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widget_manager:settings:custom_index'),
	'name' => 'params[custom_index]',
	'value' => $plugin->custom_index,
	'options_values' => [
		'0|0' => elgg_echo('option:no'),
		'1|0' => elgg_echo('widget_manager:settings:custom_index:non_loggedin'),
		'0|1' => elgg_echo('widget_manager:settings:custom_index:loggedin'),
		'1|1' => elgg_echo('widget_manager:settings:custom_index:all'),
	],
]);

$index_settings .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widget_manager:settings:widget_layout'),
	'name' => 'params[widget_layout]',
	'value' => $plugin->widget_layout,
	'options_values' => [
		'fluid' => elgg_echo('widget_manager:settings:widget_layout:fluid'),
		'33|33|33' => elgg_echo('widget_manager:settings:widget_layout:33|33|33'),
		'50|25|25' => elgg_echo('widget_manager:settings:widget_layout:50|25|25'),
		'25|50|25' => elgg_echo('widget_manager:settings:widget_layout:25|50|25'),
		'25|25|50' => elgg_echo('widget_manager:settings:widget_layout:25|25|50'),
		'75|25' => elgg_echo('widget_manager:settings:widget_layout:75|25'),
		'60|40' => elgg_echo('widget_manager:settings:widget_layout:60|40'),
		'50|50' => elgg_echo('widget_manager:settings:widget_layout:50|50'),
		'40|60' => elgg_echo('widget_manager:settings:widget_layout:40|60'),
		'25|75' => elgg_echo('widget_manager:settings:widget_layout:25|75'),
		'100' => elgg_echo('widget_manager:settings:widget_layout:100'),
	],
]);

$index_settings .= elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('widget_manager:settings:index_managers'),
	'name' => 'params[index_managers]',
	'value' => $plugin->index_managers ? explode(',', $plugin->index_managers) : null,
]);

echo elgg_view_module('info', elgg_echo('widget_manager:settings:index'), $index_settings);

if (elgg_is_active_plugin('groups')) {
	$group_settings = '';
	$group_settings .= elgg_view_field([
		'#type' => 'select',
		'#label' => elgg_echo('widget_manager:settings:group:enable'),
		'#help' => elgg_view('output/url', [
			'text' => elgg_echo('widget_manager:settings:group:force_tool_widgets'),
			'href' => 'action/widget_manager/force_tool_widgets',
			'confirm' => elgg_echo('widget_manager:settings:group:force_tool_widgets:confirm'),
		]),
		'name' => 'params[group_enable]',
		'value' => $plugin->group_enable,
		'options_values' => [
			'no' => elgg_echo('option:no'),
			'yes' => elgg_echo('widget_manager:settings:group:enable:yes'),
			'forced' => elgg_echo('widget_manager:settings:group:enable:forced'),
		],
	]);
	$group_settings .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('widget_manager:settings:group:option_default_enabled'),
		'name' => 'params[group_option_default_enabled]',
		'checked' => $plugin->group_option_default_enabled === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	]);
	$group_settings .= elgg_view_field([
		'#type' => 'checkbox',
		'#label' => elgg_echo('widget_manager:settings:group:option_admin_only'),
		'name' => 'params[group_option_admin_only]',
		'checked' => $plugin->group_option_admin_only === 'yes',
		'switch' => true,
		'default' => 'no',
		'value' => 'yes',
	]);
	
	echo elgg_view_module('info', elgg_echo('widget_manager:settings:group'), $group_settings);
}

$lazy_settings = elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_manager:settings:lazy_loading:enabled'),
	'#help' => elgg_echo('widget_manager:settings:lazy_loading:enabled:help'),
	'name' => 'params[lazy_loading_enabled]',
	'checked' => (bool) $plugin->lazy_loading_enabled,
	'switch' => true,
	'default' => 0,
	'value' => 1,
]);

$lazy_settings .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('widget_manager:settings:lazy_loading:lazy_loading_mobile_columns'),
	'#help' => elgg_echo('widget_manager:settings:lazy_loading:lazy_loading_mobile_columns:help'),
	'name' => 'params[lazy_loading_mobile_columns]',
	'checked' => (bool) $plugin->lazy_loading_mobile_columns,
	'switch' => true,
	'default' => 0,
	'value' => 1,
]);

$lazy_settings .= elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('widget_manager:settings:lazy_loading:lazy_loading_under_fold'),
	'#help' => elgg_echo('widget_manager:settings:lazy_loading:lazy_loading_under_fold:help'),
	'name' => 'params[lazy_loading_under_fold]',
	'value' => $plugin->lazy_loading_under_fold,
	'min' => 0,
]);

echo elgg_view_module('info', elgg_echo('widget_manager:settings:lazy_loading'), $lazy_settings);
