<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['no_widgets']       Optional string or Closure that will be show if there are no widgets
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_widgets'] Display the add widgets button and panel (true)
 * @uses $vars['show_access']      Show the access control (true)
 * @uses $vars['owner_guid']       Widget owner GUID (optional, defaults to page owner GUID)
 * @uses $vars['class']            Classes for the layout
 */

$num_columns = (int) elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$show_access = elgg_extract('show_access', $vars, true);
$owner_guid = elgg_extract('owner_guid', $vars);

$page_owner = elgg_get_page_owner_entity();
if ($owner_guid) {
	$owner = get_entity($owner_guid);
} else {
	$owner = $page_owner;
}

if (!$owner) {
	return;
}

// Underlying views and functions assume that the page owner is the owner of the widgets
if (empty($page_owner) || ($owner->guid !== $page_owner->guid)) {
	elgg_set_page_owner_guid($owner->guid);
}

$context = elgg_get_context();
$can_edit_layout = elgg_can_edit_widget_layout($context);

$classes = elgg_extract_class($vars, [
	'elgg-layout-widgets',
	"layout-widgets-{$context}",
]);

if ($can_edit_layout) {
	$classes[] = 'elgg-layout-can-edit';
}

$widgets = elgg_extract('widgets', $vars);
if ($widgets === null) {
	$ignore_access = $can_edit_layout ? ELGG_IGNORE_ACCESS : 0;
	$widgets = elgg_call($ignore_access, function() use ($owner, $context) {
		return elgg_get_widgets($owner->guid, $context);
	});
}

$result = '';
$no_widgets = elgg_extract('no_widgets', $vars);
if (empty($widgets) && !empty($no_widgets)) {
	if ($no_widgets instanceof \Closure) {
		echo $no_widgets();
	} else {
		$result .= $no_widgets;
	}
}

// adjusts context to get correct widgets for special widget pages
$available_widgets_context = elgg_trigger_plugin_hook('available_widgets_context', 'widget_manager', [], $context);
if ($widgets) {
	$widget_types = elgg_get_widget_types([
		'context' => $available_widgets_context,
		'container' => $owner,
	]);
}

if ($show_add_widgets && $can_edit_layout) {
	$vars['show_collapse_content'] = !in_array('widgets-fluid-columns', $classes);
	
	$result .= elgg_view('page/layouts/widgets/add_button', $vars);
}

$show_empty_grid = (bool) elgg_extract('show_empty_grid', $vars, true);
if (!$show_empty_grid && empty($result) && empty($widgets)) {
	// Restore original page owner
	if (empty($page_owner)) {
		elgg_set_page_owner_guid(false);
	} elseif ($owner->guid !== $page_owner->guid) {
		elgg_set_page_owner_guid($page_owner->guid);
	}

	return;
}

// push context after the add_button as add button uses current context
elgg_push_context('widgets');

// move hidden columns widgets to last visible column
if (!isset($widgets[$num_columns])) {
	$widgets[$num_columns] = [];
}

foreach ($widgets as $index => $column_widgets) {
	if ($index <= $num_columns) {
		continue;
	}
	
	// append widgets to last column and retain order
	foreach ($column_widgets as $column_widget) {
		$widgets[$num_columns][] = $column_widget;
	}
	unset($widgets[$index]);
}

// remove unsupported widgets
foreach ($widgets as $column_index => $column_widgets) {
	foreach ($column_widgets as $widget_index => $column_widget) {
		if (!array_key_exists($column_widget->handler, $widget_types)) {
			unset($widgets[$column_index][$widget_index]);
		}
	}
}

$grid_id = uniqid('elgg-widgets-grid-');
$grid = '';
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = (array) elgg_extract($column_index, $widgets, []);
	
	$widgets_content = '';
	foreach ($column_widgets as $widget) {
		$widgets_content .= elgg_view_entity($widget, [
			'show_access' => $show_access,
			'register_rss_link' => false,
			'layout_info' => [
				'widgets' => $widgets,
				'classes' => $classes,
			],
		]);
	}
	
	$grid .= elgg_format_element('div', [
		'id' => "elgg-widget-col-{$column_index}",
		'class' => elgg_extract_class(elgg_extract('column_classes', $vars, []), ['elgg-widgets'], $column_index),
		'data-sortable-options' => json_encode([
			'connectWith' => '#' . $grid_id . ' .elgg-widgets',
		]),
	], $widgets_content);
}

$result .= elgg_format_element('div', [
	'class' => 'elgg-widgets-grid',
	'id' => $grid_id,
], $grid);

elgg_pop_context();

$result .= elgg_view('graphics/ajax_loader', ['id' => 'elgg-widget-loader']);

echo elgg_format_element('div', [
	'class' => $classes,
	'data-page-owner-guid' => $owner->guid,
	'data-context-stack' => json_encode(elgg_get_context_stack()),
], $result);

// Restore original page owner
if (empty($page_owner)) {
	elgg_set_page_owner_guid(false);
} elseif ($owner->guid !== $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}
