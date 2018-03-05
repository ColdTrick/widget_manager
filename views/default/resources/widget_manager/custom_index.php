<?php
	
// set context
elgg_push_context('index');

elgg_set_page_owner_guid(elgg_get_site_entity()->guid); // site owns the index widgets

$num_columns = 3;

$layout = elgg_get_plugin_setting('widget_layout', 'widget_manager');
if (!empty($layout)) {
	$num_columns = count(explode('|', $layout));
}

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

$index_top_row = elgg_get_plugin_setting('index_top_row', 'widget_manager');
if (!empty($index_top_row) && ($index_top_row !== 'none')) {
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
