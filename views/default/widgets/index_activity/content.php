<?php
$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->activity_count, false);
if (empty($count)) {
	$count = 10;
}

$activity_content = $widget->activity_content;
if ($activity_content) {
	if (!is_array($activity_content)) {
		if ($activity_content == 'all') {
			unset($activity_content);
		} else {
			$activity_content = explode(',', $activity_content);
		}
	}
}

$river_options = [
	'pagination' => false,
	'limit' => $count,
	'type_subtype_pairs' => [],
	'no_results' => elgg_echo('river:none'),
];

if (!empty($activity_content)) {
	foreach ($activity_content as $content) {
		list($type, $subtype) = explode(',', $content);
		if (empty($type)) {
			continue;
		}
		
		$value = $subtype;
		if (array_key_exists($type, $river_options['type_subtype_pairs'])) {
			if (!is_array($river_options['type_subtype_pairs'][$type])) {
				$value = [$river_options['type_subtype_pairs'][$type]];
			} else {
				$value = $river_options['type_subtype_pairs'][$type];
			}
			
			$value[] = $subtype;
		}
		$river_options['type_subtype_pairs'][$type] = $value;
	}
}

echo elgg_list_river($river_options);
