<?php

$widget = elgg_extract('entity', $vars);

$content = elgg_echo('widgets:twitter_search:embed_code') . '<br />';
$content .= elgg_view('input/plaintext', [
	'name' => 'params[embed_code]',
]);
$content .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_view('output/url', [
	'href' => 'https://twitter.com/settings/widgets',
	'target' => '_blank',
	'text' => elgg_echo('widgets:twitter_search:embed_code:help'),
]));

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:twitter_search:height') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[height]',
	'value' => sanitise_int($widget->height, false),
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);
