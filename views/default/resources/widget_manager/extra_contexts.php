<?php

elgg_set_page_owner_guid(elgg_get_site_entity()->guid); // site owns the index widgets

$num_columns = 3;
$handler = elgg_extract('_route', $vars);

$layout = elgg_get_plugin_setting('widget_layout', 'widget_manager');
$index_top_row = false;

$extra_contexts_config = elgg_get_plugin_setting('extra_contexts_config', 'widget_manager');
$extra_contexts_config = json_decode($extra_contexts_config, true);
if (is_array($extra_contexts_config)) {
	$contexts_config = elgg_extract($handler, $extra_contexts_config);
	if ($contexts_config) {
		$layout = elgg_extract('layout', $contexts_config, $layout);
		$index_top_row = elgg_extract('top_row', $contexts_config);
	}
}

if (!empty($layout)) {
	$num_columns = count(explode("|", $layout));
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
