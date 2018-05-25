<?php

$pages = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'widget_page',
	'limit' => false,
	'no_results' => true,
]);

echo elgg_view_module('info', elgg_echo('widget_manager:settings:extra_contexts'), $pages, [
	'menu' => elgg_view('output/url', [
		'text' => elgg_echo('add'),
		'href' => 'ajax/form/widget_manager/widget_page',
		'class' => 'elgg-lightbox',
		'icon' => 'plus',
	]),
]);
