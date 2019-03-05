<?php
/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof WidgetPage) {
	return;
}

$vars['icon'] = false;
$vars['byline'] = false;

$title = $entity->url;
if (!empty($entity->title)) {
	$title .= " [{$entity->title}]";
}

$vars['title'] = elgg_view('output/url', [
	'text' => elgg_get_excerpt($title, 100),
	'href' => $entity->getURL(),
]);

echo elgg_view('object/elements/summary', $vars);
