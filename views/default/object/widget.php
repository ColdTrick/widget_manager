<?php
/**
 * Widget object
 *
 * @uses $vars['entity']      ElggWidget
 * @uses $vars['show_access'] Show the access control in edit area? (true)
 * @uses $vars['class']       Optional additional CSS class
 * @uses $vars['layout_info'] Additional layout info to be used by lazy loading logic
 */

use ColdTrick\WidgetManager\WidgetsSettingsConfig;

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

// show access is needed for menu items
$show_access = elgg_extract('show_access', $vars, true);
elgg_set_config('widget_show_access', $show_access);

// check if config says this widget should be hidden;
if (WidgetsSettingsConfig::instance()->getSetting($widget->handler, 'hide', (string) $widget->context)) {
	return true;
}

$widget_instance = preg_replace('/[^a-z0-9-]/i', '-', "elgg-widget-instance-{$widget->handler}");
$widget_class = elgg_extract_class($vars, $widget_instance);

$can_edit = $widget->canEdit();
if ($can_edit) {
	$widget_class[] = 'elgg-state-draggable';
	
	if (!elgg_view_exists("widgets/{$widget->handler}/edit") && elgg_extract('show_access', $vars) === false) {
		// store for determining the edit menu item
		$vars['show_edit'] = false;
	}
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

if ($widget instanceof WidgetManagerWidget && WidgetsSettingsConfig::instance()->showLazyLoaded($widget, (array) elgg_extract('layout_info', $vars, []))) {
	elgg_import_esm('widget_manager/lazy_loading');
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
