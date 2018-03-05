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

// show access is needed for menu items
$show_access = elgg_extract('show_access', $vars, true);
elgg_set_config('widget_show_access', $show_access);

// check if config says this widget should be hidden;
if (widget_manager_get_widget_setting($handler, 'hide', $widget->context)) {
	return true;
}

// $widget_header = '';
// if (($widget->widget_manager_hide_header !== 'yes') || $can_edit) {
// 	$controls = elgg_view('object/widget/elements/controls', [
// 		'widget' => $widget,
// 		'show_edit' => $can_edit,
// 	]);
		
// 	$widget_header = "<div class='elgg-widget-handle clearfix'><h3 class='elgg-widget-title'>$title</h3>$controls</div>";
// }

// $widget_body_vars = [
// 	'id' => "elgg-widget-content-{$widget->guid}",
// 	'class' => ['elgg-widget-content'],
// ];

// if ($widget->showCollapsed()) {
// 	$widget_body_vars['class'][] = 'hidden';
// }

// $widget_body = elgg_format_element('div', $widget_body_vars, elgg_view('object/widget/elements/content', $vars));

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
