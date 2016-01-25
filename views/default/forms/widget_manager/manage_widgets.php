<?php

$widget_context = elgg_extract('widget_context', $vars);

// get widgets
$exact = ($widget_context == 'groups');

$widgets = elgg_get_widget_types($widget_context, $exact);
widget_manager_sort_widgets($widgets); // sort alphabetically

if (empty($widgets)) {
	echo elgg_echo('widget_manager:forms:settings:no_widgets');
	return;
}

$table_headers = elgg_format_element('th', [], elgg_echo('widget'));
$table_headers .= elgg_format_element('th', ['class' => 'center'], elgg_echo('widget_manager:forms:settings:can_add'));
$table_headers .= elgg_format_element('th', ['class' => 'center'], elgg_echo('hide'));

$table_contents = elgg_format_element('tr', [], $table_headers);

foreach ($widgets as $handler => $widget) {
	
	$title = elgg_format_element('span', ['title' => "[{$handler}] {$widget->description}"], $widget->name);

	$check_add = elgg_view('input/checkbox', [
		'name' => "{$widget_context}_{$handler}_can_add",
		'value' => 'yes',
		'checked' => widget_manager_get_widget_setting($handler, 'can_add', $widget_context),
	]);

	$check_hide = elgg_view('input/checkbox', [
		'name' => "{$widget_context}_{$handler}_hide",
		'value' => 'yes',
		'checked' => widget_manager_get_widget_setting($handler, 'hide', $widget_context),
	]);
			
	$row = elgg_format_element('td', [], $title);
	$row .= elgg_format_element('td', ['class' => 'center'], $check_add);
	$row .= elgg_format_element('td', ['class' => 'center'], $check_hide);
			
	$table_contents .= elgg_format_element('tr', [], $row);
}

$body = elgg_format_element('table', ['class' => 'elgg-table mbm'], $table_contents);
$body .= elgg_view('input/hidden', ['value' => $widget_context, 'name' => 'widget_context']);
$body .= elgg_view('input/submit', ['value' => elgg_echo('save')]);

echo $body;