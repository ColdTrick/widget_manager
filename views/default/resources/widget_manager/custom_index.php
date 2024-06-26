<?php

elgg_push_context('index');

elgg_set_page_owner_guid(elgg_get_site_entity()->guid); // site owns the index widgets

$num_columns = 3;

$layout = elgg_get_plugin_setting('widget_layout', 'widget_manager');
if (!empty($layout)) {
	$num_columns = count(explode('|', $layout));
}

$classes = [];
$column_classes = [];

switch ($layout) {
	case 'fluid':
		$classes[] = 'widgets-1-columns';
		$classes[] = 'widgets-fluid-columns';
		$num_columns = 1;
		
		elgg_import_esm('widget_manager/fluid');
		break;
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

// draw the page
$content = elgg_view_layout('widgets', [
	'class' => $classes,
	'num_columns' => $num_columns,
	'column_classes' => $column_classes,
	'exact_match' => true,
]);

echo elgg_view_page('', ['content' => $content]);
