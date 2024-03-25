<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \WidgetPage) {
	return;
}

$vars['access'] = false;
$vars['byline'] = false;
$vars['time'] = false;

echo elgg_view('search/entity/default', $vars);
