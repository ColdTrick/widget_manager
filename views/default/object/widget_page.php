<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \WidgetPage) {
	return;
}

if (!elgg_extract('full_view', $vars)) {
	$vars['icon'] = false;
	$vars['byline'] = false;
	
	$title = $entity->getDisplayName() . " [{$entity->url}]";
	
	$vars['title'] = elgg_view_url($entity->getURL(), elgg_get_excerpt($title, 100));
	
	echo elgg_view('object/elements/summary', $vars);
	return;
}

elgg_push_context('index');
elgg_set_page_owner_guid($entity->guid);

$num_columns = $entity->getNumColumns();
$layout = $entity->layout;

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

if ($entity->show_description !== false && !empty($entity->description)) {
	echo elgg_view('output/longtext', ['value' => $entity->description, 'class' => 'widget-page-description']);
}

echo elgg_view_layout('widgets', [
	'class' => $classes,
	'num_columns' => $num_columns,
	'column_classes' => $column_classes,
	'exact_match' => true,
	'show_add_widgets' => false,
]);
