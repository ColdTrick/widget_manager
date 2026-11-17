<?php

elgg_register_menu_item('title', [
	'name' => 'add_widget_page',
	'icon' => 'plus',
	'text' => elgg_echo('add'),
	'href' => elgg_generate_url('ajax', [
		'type' => 'form',
		'segments' => 'widget_manager/widget_page',
	]),
	'class' => 'elgg-lightbox elgg-button elgg-button-action',
]);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'widget_page',
	'limit' => false,
	'no_results' => true,
	'sort_by' => [
		'property' => 'url',
		'direction' => 'asc',
	],
]);
