<?php

$widget = elgg_extract('entity', $vars);

$max_slider_options = 5;

$seconds_per_slide = sanitise_int($widget->seconds_per_slide);
if (empty($seconds_per_slide)) {
	$seconds_per_slide = 10;
}

$slider_height = sanitise_int($widget->slider_height);
if (empty($slider_height)) {
	$slider_height = 300;
}

$overlay_color = $widget->overlay_color;
if (empty($overlay_color)) {
	$overlay_color = '4690D6';
}

$direction_options_values = [
	'top' => elgg_echo('top'),
	'right' => elgg_echo('right'),
	'bottom' => elgg_echo('bottom'),
	'left' => elgg_echo('left'),
];

$slider_type_options = [
	's3slider' => elgg_echo('widget_manager:widgets:image_slider:slider_type:s3slider'),
	'flexslider' => elgg_echo('widget_manager:widgets:image_slider:slider_type:flexslider'),
];

for ($i = 1; $i <= $max_slider_options; $i++) {
		
	$direction = $widget->{'slider_' . $i . '_direction'};
	if (empty($direction)) {
		$direction = 'top';
	}
	
	$slider_settings_label = elgg_format_element('label', ['onclick' => '$(this).next().toggle();'], elgg_echo('widget_manager:widgets:image_slider:title') . ' - ' . $i);

	$slider_settings = elgg_format_element('div', [], elgg_echo('widget_manager:widgets:image_slider:label:url'));
	$slider_settings .= elgg_view('input/text', [
		'name' => 'params[slider_' . $i . '_url]',
		'value' => $widget->{'slider_' . $i . '_url'},
	]);

	$slider_settings .= elgg_format_element('div', [], elgg_echo('widget_manager:widgets:image_slider:label:text'));
	$slider_settings .= elgg_view('input/text', [
		'name' => 'params[slider_' . $i . '_text]',
		'value' => $widget->{'slider_' . $i . '_text'},
	]);

	$slider_settings .= elgg_format_element('div', [], elgg_echo('widget_manager:widgets:image_slider:label:link'));
	$slider_settings .= elgg_view('input/text', [
		'name' => 'params[slider_' . $i . '_link]',
		'value' => $widget->{'slider_' . $i . '_link'},
	]);

	$slider_settings .= elgg_format_element('div', [], elgg_echo('widget_manager:widgets:image_slider:label:direction'));
	$slider_settings .= elgg_view('input/dropdown', [
		'name' => 'params[slider_' . $i . '_direction]',
		'value' => $direction,
		'options_values' => $direction_options_values,
	]);
	
	$slider_settings = elgg_format_element('div', [], $slider_settings);
	echo elgg_format_element('span', ['class' => 'image_slider_settings'], $slider_settings_label . $slider_settings);
	echo elgg_format_element('br');
}

echo elgg_format_element('hr');

$content = elgg_echo('widget_manager:widgets:image_slider:slider_type') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[slider_type]',
	'value' => $widget->slider_type,
	'options_values' => $slider_type_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widget_manager:widgets:image_slider:seconds_per_slide') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[seconds_per_slide]',
	'value' => $seconds_per_slide,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widget_manager:widgets:image_slider:slider_height') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[slider_height]',
	'value' => $slider_height,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widget_manager:widgets:image_slider:overlay_color') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[overlay_color]',
	'value' => $overlay_color,
	'size' => 6,
	'maxlength' => 6,
]);

echo elgg_format_element('div', [], $content);
