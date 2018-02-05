<?php

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$custom_more_title = $widget->widget_manager_custom_more_title;
$custom_more_url = $widget->widget_manager_custom_more_url;

if (empty($custom_more_title) || empty($custom_more_url)) {
	return;
}

$custom_more_link = elgg_view('output/url', [
	'text' => $custom_more_title,
	'href' => $custom_more_url,
]);

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $custom_more_link);
