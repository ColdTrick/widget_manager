<?php

$widget = elgg_extract('entity', $vars);

$rss_count = sanitise_int($widget->rss_count, false);
if (empty($rss_count)) {
	$rss_count = 4;
}

$yesno_options = [
	'yes' => elgg_echo('option:yes'),
	'no' => elgg_echo('option:no'),
];

$noyes_options = array_reverse($yesno_options);

$content = elgg_echo('widgets:rss:settings:rssfeed') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[rssfeed]',
	'value' => $widget->rssfeed,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:rss_count') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[rss_count]',
	'value' => $rss_count,
	'options' => range(1,10),
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:rss_cachetimeout') . ' ';
$content .= elgg_view('input/text', [
	'name' => 'params[rss_cachetimeout]',
	'value' => $widget->rss_cachetimeout,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:show_feed_title') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_feed_title]',
	'value' => $widget->show_feed_title,
	'options_values' => $noyes_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss_server:settings:show_author') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_author]',
	'value' => $widget->show_author,
	'options_values' => $noyes_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:excerpt') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[excerpt]',
	'value' => $widget->excerpt,
	'options_values' => $yesno_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:show_item_icon') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_item_icon]',
	'value' => $widget->show_item_icon,
	'options_values' => $noyes_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:post_date') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[post_date]',
	'value' => $widget->post_date,
	'options_values' => $yesno_options,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('widgets:rss:settings:show_in_lightbox') . ' ';
$content .= elgg_view('input/dropdown', [
	'name' => 'params[show_in_lightbox]',
	'value' => $widget->show_in_lightbox,
	'options_values' => $noyes_options,
]);

echo elgg_format_element('div', [], $content);
	