<?php
$context = $vars["context"];
$show_access = (int) $vars["show_access"];

if ($md_guid = get_input("multi_dashboard_guid")) {
	$params = [
	'name' => 'widget_context',
		'value' => $context . "_" . $md_guid
	];
} else {
	$params = [
		'name' => 'widget_context',
	'value' => $context
	];
}
echo elgg_view('input/hidden', $params);

echo elgg_view('input/hidden', ["name" => "show_access", "value" => $show_access]);
	
$widget_context = str_replace("default_", "", $context);

$available_widgets_context = elgg_trigger_plugin_hook("available_widgets_context", "widget_manager", array(), $widget_context);

$widgets = elgg_get_widget_types($available_widgets_context, $vars["exact_match"]);
if (empty($widgets)) {
	$widgets = array();
}

widget_manager_sort_widgets($widgets);

$current_handlers = array();
if (!empty($vars["widgets"])) {
	// check for already used widgets
	foreach ($vars["widgets"] as $column_widgets) {
		// foreach column
		foreach ($column_widgets as $widget) {
			// for each widgets
			$current_handlers[] = $widget->handler;
		}
	}
}

$title = elgg_echo("widget_manager:widgets:lightbox:title:" . $context);

$search .= elgg_format_element('input', [
	'class' => 'elgg-input-text',
	'title' => elgg_echo("search"),
	'type' => 'text',
	'value' => elgg_echo("search"),
	'onfocus' => "if($(this).val() == \"" . elgg_echo("search") . "\"){ $(this).val(\"\"); }",
	'onkeyup' => 'elgg.widget_manager.widgets_search($(this).val());',
]);

$search = elgg_format_element('div', ['class' => 'wm-add-panel-search'], $search);

$widgets_list = [];

	foreach ($widgets as $handler => $widget) {

	$widget_class = ['elgg-item'];

	$allow_multiple = $widget->multiple;
		$can_add = widget_manager_get_widget_setting($handler, "can_add", $widget_context);
		$hide = widget_manager_get_widget_setting($handler, "hide", $widget_context);
		
	if (!$can_add || $hide) {
		continue;
	}
			
			if (!$allow_multiple && in_array($handler, $current_handlers)) {
		$widget_class[] = 'elgg-state-unavailable';
			} else {
		$widget_class[] = 'elgg-state-available';
			}
			
			if ($allow_multiple) {
		$widget_class[] = 'elgg-widget-multiple';
			} else {
		$widget_class[] = 'elgg-widget-single';
			}
			
	$add = elgg_view('output/url', [
		'text' => elgg_view_icon('plus'),
		'href' => '#',
		'class' => 'elgg-button elgg-button-action wm-add-widget',
	]);
			
	$actions = elgg_format_element('li', [
		'data-elgg-widget-type' => $handler,
		'class' => $widget_class,
			], $add);

	$image = elgg_format_element('ul', ['class' => 'widget_manager_widgets_lightbox_actions'], $actions);

	$summary = elgg_view('object/elements/summary', [
		'entity' => $widget,
		'title' => $widget->name,
		'subtitle' => $widget->description,
	]);

	$body = elgg_view_image_block($image, $summary, [
		'class' => 'widget_manager_widgets_lightbox_wrapper',
	]);

	$widgets_list[] = elgg_format_element('li', ['class' => 'elgg-item'], $body);
			}
			
			
if (empty($widgets_list)) {
	$body = elgg_format_element('p', ['class' => 'elgg-noresults'], elgg_echo("notfound"));
} else {
	$body = elgg_format_element('ul', ['class' => 'elgg-list'], implode('', $widgets_list));
}

$module = elgg_view_module('main', $title . $search, $body, [
	'id' => 'widget_manager_widgets_select',
		]);

echo elgg_format_element('div', ['class' => 'elgg-widgets-add-panel hidden'], $module);
?>
<script type="text/javascript">
	require(['widget_manager/add_panel']);
</script>
