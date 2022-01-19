<?php
use ColdTrick\WidgetManager\WidgetsSettingsConfig;

/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 * @uses $vars['layout_info'] Additional layout info to be used by lazy loading logic
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
if (WidgetsSettingsConfig::instance()->getSetting($handler, 'hide', (string) $widget->context)) {
	return true;
}

$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-$handler");

$can_edit = $widget->canEdit();

$widget_class = elgg_extract_class($vars, $widget_instance);
if ($can_edit) {
	$widget_class[] = 'elgg-state-draggable';
}

if ($widget->widget_manager_custom_class) {
	$widget_class[] = $widget->widget_manager_custom_class; // optional custom class for this widget
}

if ($widget->widget_manager_hide_header == 'yes') {
	$widget_class[] = 'widget_manager_hide_header';
	if ($can_edit) {
		$widget_class[] = 'widget_manager_hide_header_admin';
	}
}

if ($widget->widget_manager_disable_widget_content_style == 'yes') {
	$widget_class[] = 'widget_manager_disable_widget_content_style';
}

// need to check class because theme sandbox creates unsaved ElggWidget entities, but will try to show them using this view
if ($widget instanceof WidgetManagerWidget && $widget->showCollapsed()) {
	$widget_class[] = 'elgg-state-collapsed';
}

if ($widget instanceof WidgetManagerWidget && WidgetsSettingsConfig::instance()->showLazyLoaded($widget, (array) elgg_extract('layout_info', $vars, []))) {
	$body = elgg_view('graphics/ajax_loader', ['hidden' => false]);
	$widget_class[] = 'lazy-loading';
} else {
	$body = elgg_view('object/widget/body', $vars);
}

echo elgg_view_module('widget', '', $body, [
	'class' => $widget_class,
	'id' => "elgg-widget-{$widget->guid}",
	'header' => elgg_view('object/widget/header', $vars),
]);
