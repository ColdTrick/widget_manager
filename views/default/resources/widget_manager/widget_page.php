<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$handler = elgg_extract('_route', $vars);

$pages = elgg_get_entities([
	'type' => 'object',
	'subtype' => \WidgetPage::SUBTYPE,
	'metadata_name_value_pairs' => ['url' => $handler],
	'limit' => 1,
]);

if (empty($pages)) {
	throw new EntityNotFoundException();
}

$widget_page = $pages[0];

elgg_push_context('index');
elgg_set_page_owner_guid($widget_page->guid);

if ($widget_page->canEdit()) {
	$href = elgg_generate_url('widgets:add_panel', [
		'context' => elgg_get_context(),
		'context_stack' => elgg_get_context_stack(),
		'show_access' => true,
		'owner_guid' => elgg_get_page_owner_guid(),
	]);

	elgg_register_menu_item('title', [
		'name' => 'widgets_add',
		'href' => false,
		'icon' => 'plus',
		'text' => elgg_echo('widgets:add'),
		'link_class' => ['elgg-lightbox', 'elgg-button', 'elgg-button-action'],
		'data-colorbox-opts' => json_encode([
			'href' => $href,
			'maxWidth' => '900px',
			'maxHeight' => '90%',
		]),
	]);
	
	elgg_register_menu_item('title', [
		'name' => 'hide-widget-contents',
		'link_class' => ['elgg-button', 'elgg-button-action'],
		'text' => elgg_echo('widget_manager:layout:content:hide'),
		'icon' => 'eye-slash',
		'href' => false,
		'priority' => 80,
	]);
	
	elgg_register_menu_item('title', [
		'name' => 'show-widget-contents',
		'link_class' => ['elgg-button', 'elgg-button-action'],
		'item_class' => 'hidden',
		'text' => elgg_echo('widget_manager:layout:content:show'),
		'icon' => 'eye',
		'href' => false,
		'priority' => 81,
	]);
}

echo elgg_view_page($widget_page->getDisplayName(), [
	'title' => $widget_page->title ?: false,
	'content' => elgg_view_entity($widget_page),
	'entity' => $widget_page,
]);
