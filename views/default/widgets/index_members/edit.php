<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->member_count, false);
if (empty($count)) {
	$count = 8;
}

$user_icon_options_values = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];
	
$content = elgg_echo('widget:numbertodisplay') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[member_count]',
	'value' => $count,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widget_manager:widgets:index_members:user_icon') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[user_icon]',
	'options_values' => $user_icon_options_values,
	'value' => $widget->user_icon,
]);

echo elgg_format_element('div', [], $content);
