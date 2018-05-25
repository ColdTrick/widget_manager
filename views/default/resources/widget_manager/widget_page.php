<?php

$handler = elgg_extract('_route', $vars);

$pages = elgg_get_entities([
	'type' => 'object',
	'subtype' => \WidgetPage::SUBTYPE,
	'metadata_name_value_pairs' => ['url' => $handler],
	'limit' => 1,
]);

if (empty($pages)) {
	throw new \Elgg\EntityNotFoundException();
}

$widget_page = $pages[0];

elgg_push_context('index');
elgg_set_page_owner_guid($widget_page->guid);

$num_columns = $widget_page->getNumColumns();
$layout = $widget_page->layout;
$top_row = $widget_page->top_row;

$num_columns = 3;

$classes = [];

switch ($layout) {
	case '33|33|33':
		$classes[] = 'widgets-3-columns';
		break;
	case '50|50':
		$classes[] = 'widgets-2-columns';
		break;
	default:
		$classes[] = "widgets-{$num_columns}-columns";
		break;
}

if (!empty($top_row) && ($top_row !== 'none')) {
	$num_columns++;
	$classes[] = 'widgets-top-row';
}

// draw the page
$content = elgg_view_layout('widgets', [
	'class' => $classes,
	'num_columns' => $num_columns,
	'exact_match' => true,
]);

$body = elgg_view_layout('one_column', [
	'content' => $content,
	'title' => false,
]);

echo elgg_view_page('', $body);
