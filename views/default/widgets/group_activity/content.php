<?php 

$widget = $vars["entity"];
/* @var ElggWidget $widget */

$group_guid = $widget->owner_guid;

$count = (int) $widget->activity_count;
if (! $count) {
    $count = 10;
}

$db_prefix = elgg_get_config('dbprefix');
$river_options = array(
    "pagination" => false,
    "limit" => $count,
    'joins' => array("JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid"),
    'wheres' => array("(e1.container_guid = $group_guid)"),
);
$activity = elgg_list_river($river_options);

if (empty($activity)) {
    $activity = elgg_echo("river:none");
}

echo $activity;
