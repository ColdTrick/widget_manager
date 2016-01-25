<?php

$widget = elgg_extract('entity', $vars);

$height = sanitise_int($widget->height, false);

$widget_id = $widget->widget_id;

if (empty($widget_id)) {
	echo elgg_echo('widgets:twitter_search:not_configured');
	return;
}

$options = [
	'class' => 'twitter-timeline',
	'data-dnt' => 'true',
	'data-widget-id' => $widget_id,
];

if ($height) {
	$options['height'] = $height;
}

echo elgg_view('output/url', $options);

echo elgg_format_element('script', [], 'require(["widget_manager/widgets/twitter_search"], function (twitter_search) { twitter_search(); });');
