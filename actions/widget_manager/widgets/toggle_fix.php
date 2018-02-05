<?php
	
$entity_guid = (int) get_input('guid');
$entity = get_entity($entity_guid);

if (!$entity instanceof ElggWidget) {
	return elgg_error_response();
}

$current = $entity->fixed;
if ($current) {
	$entity->fixed = false;
} else {
	$entity->fixed = true;
}

// trigger save event for registration of change to status
$entity->save();
	