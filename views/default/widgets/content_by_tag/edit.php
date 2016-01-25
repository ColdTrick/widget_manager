<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->content_count, false);
if (empty($count)) {
	$count = 8;
}

$content_type = $widget->content_type;

$content_options_values = [];
foreach (widget_manager_widgets_content_by_tag_get_supported_content() as $plugin => $subtype) {
	if (elgg_is_active_plugin($plugin)) {
		$content_options_values[$subtype] = elgg_echo("item:object:{$subtype}");
	}
}

if (empty($content_type) && !empty($content_options_values)) {
	$keys = array_keys($content_options_values);
	$content_type = $keys[0];
}

$tags_option = $widget->tags_option;

if (empty($tags_option)) {
	$tags_option = 'and';
}

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

$noyes_options = array_reverse($yesno_options);

$tags_options_values = [
	'and' => elgg_echo('widgets:content_by_tag:tags_option:and'),
	'or' => elgg_echo('widgets:content_by_tag:tags_option:or'),
];

$display_option_options_values = [
	'normal' => elgg_echo('widgets:content_by_tag:display_option:normal'),
	'simple' => elgg_echo('widgets:content_by_tag:display_option:simple'),
	'slim' => elgg_echo('widgets:content_by_tag:display_option:slim'),
];

$owner_guids = elgg_echo('widgets:content_by_tag:owner_guids') . '<br />';
$owner_guids .= elgg_view('input/hidden', ['name' => 'params[owner_guids]', 'value' => 0]);
$owner_guids .= elgg_view('input/userpicker', ['name' => 'params[owner_guids]', 'values' => $widget->owner_guids]);
$owner_guids .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widgets:content_by_tag:owner_guids:description'));
echo elgg_format_element('div', [], $owner_guids);

if ($widget->context == 'groups') {
	$group_only = elgg_echo('widgets:content_by_tag:group_only') . '<br />';
	$group_only .= elgg_view('input/dropdown', [
		'name' => 'params[group_only]',
		'options_values' => $yesno_options,
		'value' => $widget->group_only,
	]);
	echo elgg_format_element('div', [], $group_only);
} elseif(elgg_view_exists('input/grouppicker')) {
	$container_guids = elgg_echo('widgets:content_by_tag:container_guids') . '<br />';
	$container_guids .= elgg_view('input/hidden', ['name' => 'params[container_guids]', 'value' => 0]);
	$container_guids .= elgg_view('input/grouppicker', ['name' => 'params[container_guids]', 'values' => $widget->container_guids]);
	$container_guids .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widgets:content_by_tag:container_guids:description'));
	echo elgg_format_element('div', [], $container_guids);
}

$content = elgg_echo('widget:numbertodisplay') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[content_count]',
	'value' => $count,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:entities') . '<br />';
$content .= elgg_view('input/checkboxes', [
	'name' => 'params[content_type]',
	'value' => $content_type,
	'options' => array_flip($content_options_values),
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:tags') . '<br />';
$content .= elgg_view('input/tags', [
	'name' => 'params[tags]',
	'value' => $widget->tags,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:tags_option') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[tags_option]',
	'value' => $tags_option,
	'options_values' => $tags_options_values,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:excluded_tags') . '<br />';
$content .= elgg_view('input/tags', [
	'name' => 'params[excluded_tags]',
	'value' => $widget->excluded_tags,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:show_search_link') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_search_link]',
	'value' => $widget->show_search_link,
	'options_values' => $noyes_options,
]);
$content .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widgets:content_by_tag:show_search_link:disclaimer'));

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:content_by_tag:display_option') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[display_option]',
	'value' => $widget->display_option,
	'options_values' => $display_option_options_values,
]);

echo elgg_format_element('div', [], $content);

$display_options = '';

$content = elgg_echo('widgets:content_by_tag:highlight_first') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[highlight_first]',
	'value' => $widget->highlight_first,
]);

$display_options .= elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options-slim'], $content);

$content = elgg_echo('widgets:content_by_tag:show_avatar') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_avatar]',
	'value' => $widget->show_avatar,
	'options_values' => $yesno_options,
]);

$display_options .= elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options-simple widgets-content-by-tag-display-options-slim'], $content);

$content = elgg_echo('widgets:content_by_tag:show_timestamp') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_timestamp]',
	'value' => $widget->show_timestamp,
	'options_values' => $yesno_options,
]);

$display_options .= elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options-simple widgets-content-by-tag-display-options-slim'], $content);

echo elgg_format_element('div', ['class' => 'widgets-content-by-tag-display-options'], $display_options);

$content = elgg_echo('widgets:content_by_tag:order_by') . '<br />';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[order_by]',
	'value' => $widget->order_by,
	'options_values' => [
		'time_created' => elgg_echo('widgets:content_by_tag:order_by:time_created'),
		'alpha' => elgg_echo('widgets:content_by_tag:order_by:alpha'),
	],
]);

echo elgg_format_element('div', [], $content);

echo elgg_format_element('script', [], 'require(["widget_manager/widgets/content_by_tag"], function() { init(); })');
