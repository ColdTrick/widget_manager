<?php

$entity = elgg_extract('entity', $vars);

echo elgg_format_element('div', ['class' => 'elgg-subtext mbm'], elgg_echo('widget_manager:settings:extra_contexts:description'));

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity ? $entity->guid: null,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('widget_manager:settings:extra_contexts:page'),
	'name' => 'url',
	'value' => $entity ? $entity->url : null,
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'#help' => elgg_echo('widget_manager:widget_page:title:help'),
	'name' => 'title',
	'value' => $entity ? $entity->title : null,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widget_manager:settings:extra_contexts:layout'),
	'name' => 'layout',
	'options_values' => [
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
	'value' => $entity ? $entity->layout : null,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('widget_manager:settings:extra_contexts:top_row'),
	'name' => 'top_row',
	'options_values' => [
		'none' => elgg_echo('widget_manager:settings:index_top_row:none'),
		'full_row' => elgg_echo('widget_manager:settings:index_top_row:full_row'),
		'two_column_left' => elgg_echo('widget_manager:settings:index_top_row:two_column_left'),
	],
	'value' => $entity ? $entity->top_row : null,
]);

echo elgg_view_field([
	'#type' => 'userpicker',
	'#label' => elgg_echo('widget_manager:settings:extra_contexts:manager'),
	'name' => 'manager',
	'value' => $entity ? $entity->getManagers() : null,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => $entity ? elgg_echo('update') : elgg_echo('save'),
]);

elgg_set_form_footer($footer);
