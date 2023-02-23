<?php

$handler = get_input('handler');
$context = get_input('context');

if (empty($handler) || empty($context)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entities = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'widget',
	'metadata_name_value_pairs' => [
		'handler' => $handler,
		'context' => $context,
	],
	'limit' => false,
	'batch' => true,
	'batch_inc_offset' => false,
]);

foreach ($entities as $entity) {
	$entity->delete();
}

return elgg_ok_response('', elgg_echo('entity:delete:success', [elgg_echo('collection:object:widget')]));
