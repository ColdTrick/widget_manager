<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 */

$widget = elgg_extract('entity', $vars);
if (!($widget instanceof \ElggWidget)) {
	return;
}

$handler = $widget->handler;

$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-$handler");

$can_edit = $widget->canEdit();

$widget_class = elgg_extract_class($vars, $widget_instance);
$widget_class[] = $can_edit ? 'elgg-state-draggable' : 'elgg-state-fixed';

if ($widget->widget_manager_custom_class) {
	$widget_class[] = $widget->widget_manager_custom_class; // optional custom class for this widget
}

if ($widget->widget_manager_hide_header == 'yes') {
	if ($can_edit) {
		$widget_class[] = 'widget_manager_hide_header_admin';
	} else {
		$widget_class[] = 'widget_manager_hide_header';
	}
}

if ($widget->widget_manager_disable_widget_content_style == 'yes') {
	$widget_class[] = 'widget_manager_disable_widget_content_style';
}

$body = elgg_view('object/widget/body', $vars);

echo elgg_view_module('widget', '', $body, [
	'class' => $widget_class,
	'id' => "elgg-widget-$widget->guid",
	'header' => elgg_view('object/widget/header', $vars),
]);
