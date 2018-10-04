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

$classes = [];
$column_classes = [];

switch ($layout) {
	case '33|33|33':
		$classes[] = 'widgets-3-columns';
		break;
	case '50|50':
		$classes[] = 'widgets-2-columns';
		break;
	default:
		$classes[] = "widgets-{$num_columns}-columns";
		
		$columns = array_reverse(explode('|', $layout));
		foreach ($columns as $column_index => $column_width) {
			$column_classes[$column_index + 1] = "col-width-{$column_width}";
		}
		break;
}

if (!empty($top_row) && ($top_row !== 'none')) {
	$num_columns++;
	$classes[] = 'widgets-top-row';
}


$title = $widget_page->title ?: false;
if ($title && elgg_can_edit_widget_layout($widget_page->context)) {
	
	$href = elgg_normalize_url(elgg_http_add_url_query_elements('widgets/add_panel', [
		'context' => elgg_get_context(),
		'context_stack' => elgg_get_context_stack(),
		'show_access' => true,
		'owner_guid' => elgg_get_page_owner_guid(),
	]));

	elgg_register_menu_item('title', [
		'name' => 'widgets_add',
		'href' => false,
		'icon' => 'plus',
		'text' => elgg_echo('widgets:add'),
		'link_class' => 'elgg-lightbox elgg-button elgg-button-action',
		'data-colorbox-opts' => json_encode([
			'href' => $href,
			'maxWidth' => '900px',
			'maxHeight' => '90%',
		]),
	]);
}

// draw the page
$content = elgg_view_layout('widgets', [
	'class' => $classes,
	'num_columns' => $num_columns,
	'column_classes' => $column_classes,
	'exact_match' => true,
	'show_add_widgets' => empty($title),
]);

$body = elgg_view_layout('one_column', [
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page('', $body);
