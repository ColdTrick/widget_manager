<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->member_count, false);
if (empty($count)) {
	$count = 8;
}

$options = [
	'type' => 'user',
	'limit' => $count,
	'relationship' => 'member_of_site',
	'relationship_guid' => elgg_get_site_entity()->getGUID(),
	'inverse_relationship' => true,
	'wheres' => ['ue.last_action >= ' . (time() - 600)],
	'joins' => ['JOIN ' . elgg_get_config('dbprefix') . 'users_entity ue ON e.guid = ue.guid'],
	'order_by' => 'ue.last_action desc',
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'users',
	'gallery_class' => 'elgg-gallery-users',
	'size' => 'small',
	'no_results' => elgg_echo('widget_manager:widgets:index_members_online:no_result'),
];

if ($widget->user_icon == 'yes') {
	$options['metadata_name'] = 'icontime';
}

echo elgg_list_entities_from_relationship($options);