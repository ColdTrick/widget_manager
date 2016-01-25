<?php

$widget = elgg_extract('entity', $vars);

$content = elgg_echo('widgets:free_html:settings:html_content') . '<br />';
$content .= elgg_view('input/longtext', [
	'name' => 'params[html_content]',
	'value' => $widget->html_content,
]);

echo elgg_format_element('div', [], $content);
