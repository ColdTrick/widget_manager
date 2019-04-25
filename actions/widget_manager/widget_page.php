<?php
	
$entity_guid = (int) get_input('guid');
if ($entity_guid) {
	$entity = get_entity($entity_guid);
	if (!$entity instanceof WidgetPage) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	}
}

$url = get_input('url');
if (empty($url)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!isset($entity)) {
	$entity = new WidgetPage();
}

$entity->url = $url;
$entity->title = get_input('title');
$entity->layout = get_input('layout');
$entity->top_row = get_input('top_row');

$entity->save();

$entity->setManagers((array) get_input('manager', []));

elgg_delete_system_cache('widget_pages');

return elgg_ok_response('', elgg_echo('save:success'));
