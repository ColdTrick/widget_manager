<?php
$widget = elgg_extract('entity', $vars);

$content = elgg_echo('widgets:iframe:settings:iframe_url') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[iframe_url]',
	'value' => $widget->iframe_url,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:iframe:settings:iframe_height') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[iframe_height]',
	'value' => $widget->iframe_height,
]);

echo elgg_format_element('div', [], $content);
