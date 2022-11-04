<?php

$guids = get_input('guids');
if (empty($guids)) {
	return elgg_ok_response();
}

foreach ($guids as $index => $guid) {
	$widget = elgg_call(ELGG_IGNORE_ACCESS, function() use ($guid) {
		return get_entity($guid);
	});
	
	if (!$widget instanceof \ElggWidget || !$widget->canEdit()) {
		continue;
	}
	
	$widget->column = 1;
	$widget->order = $index + 1;
}

return elgg_ok_response();
