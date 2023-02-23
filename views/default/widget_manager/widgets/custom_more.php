<?php

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$custom_more_title = $widget->widget_manager_custom_more_title;
$custom_more_url = $widget->widget_manager_custom_more_url;

if (empty($custom_more_title) || empty($custom_more_url)) {
	return;
}

echo elgg_view('page/components/list/widget_more', ['widget_more' => elgg_view_url($custom_more_url, $custom_more_title)]);
