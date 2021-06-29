<?php

$guids = get_input('guids');
if (empty($guids)) {
	return elgg_ok_response();
}

foreach ($guids as $index => $guid) {
	$widget = get_entity($guid);
	if (!$widget instanceof \ElggWidget || !$widget->canEdit()) {
		continue;
	}
	
	$widget->order = $index + 1;
}

return elgg_ok_response();
