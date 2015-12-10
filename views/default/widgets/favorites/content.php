<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->num_entities);
if (empty($count)) {
	$count = 10;
}

$entities = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'widget_favorite',
	'limit' => $count,
	'offset' => 0,
	'owner_guid' => $widget->getOwnerGUID(),
]);

if ($entities) {
	$list_items = '';
	foreach ($entities as $entity) {

		$link = elgg_view("output/url", [
			"text" => $entity->title,
			"href" => $entity->description,
		]);
		$remove_icon = elgg_view("output/url", [
			"text" => elgg_view_icon("delete-alt"),
			"is_action" => true,
			"href" => "action/favorite/toggle?link=" . $entity->description,
			"class" => "widgets-favorite-entity-delete mls",
		]);
		$list_items .= elgg_format_element('li', ['class' => 'elgg-item'], $link . $remove_icon);
	}
	echo elgg_format_element('ul', ['class' => 'elgg-list'], $list_items);
	
} else {
	echo elgg_echo('notfound');
	echo '<br /><br />';
	echo elgg_echo('widgets:favorites:content:more_info');
}
