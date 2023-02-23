<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \WidgetPage) {
	return;
}

$vars['icon'] = false;
$vars['byline'] = false;

$title = $entity->getDisplayName() . " [{$entity->url}]";

$vars['title'] = elgg_view_url($entity->getURL(), elgg_get_excerpt($title, 100));

echo elgg_view('object/elements/summary', $vars);
