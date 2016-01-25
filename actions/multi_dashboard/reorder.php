<?php

$order = get_input('order');

if (empty($order)) {
	register_error(elgg_echo('widget_manager:actions:multi_dashboard:reorder:error:order'));
	forward();
}

if (!is_array($order)) {
	$order = [$order];
}

foreach ($order as $pos => $guid) {
	$dashboard = get_entity($guid);
	
	if (!elgg_instanceof($dashboard, 'object', MultiDashboard::SUBTYPE, 'MultiDashboard')) {
		continue;
	}
	
	$dashboard->order = ($pos + 1);
}

system_message(elgg_echo('widget_manager:actions:multi_dashboard:reorder:success'));
