<?php

$guids = get_input('guids');
if (empty($guids)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$page_owner_guid = (int) get_input('page_owner_guid');
if (!empty($page_owner_guid)) {
	elgg_set_page_owner_guid($page_owner_guid);
}

$old_context_stack = elgg_get_context_stack();
$context_stack = (array) get_input('context_stack');
if (!empty($context_stack)) {
	elgg_set_context_stack($context_stack);
}

elgg_push_context('widgets');

$entities = elgg_get_entities([
	'guids' => $guids,
	'type' => 'object',
	'subtype' => 'widget',
	'limit' => false,
]);

$result = [];
foreach ($entities as $entity) {
	$result[$entity->guid] = elgg_view('object/widget/body', ['entity' => $entity]);
}

elgg_pop_context();
elgg_set_context_stack($old_context_stack);

return elgg_ok_response($result);
