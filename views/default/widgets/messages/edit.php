<?php

$widget = elgg_extract('entity', $vars);

$max_messages = sanitise_int($widget->max_messages, false);
if (empty($max_messages)) {
	$max_messages = 5;
}

$yes_no_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

$content = elgg_echo('widget:numbertodisplay') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[max_messages]',
	'value' => $max_messages,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:messages:settings:only_unread') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[only_unread]',
	'value' => $widget->only_unread,
	'options_values' => $yes_no_options,
]);

echo elgg_format_element('div', [], $content);
