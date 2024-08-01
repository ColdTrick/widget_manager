<?php
	
$entity_guid = (int) get_input('guid');
$entity = null;

if ($entity_guid) {
	$entity = get_entity($entity_guid);
	if (!$entity instanceof \WidgetPage) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	} elseif (!$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
}

$url = get_input('url');
if (empty($url) && !($entity instanceof \WidgetPage && !elgg_is_admin_logged_in())) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$entity instanceof \WidgetPage) {
	if (!elgg_is_admin_logged_in()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	$entity = new \WidgetPage();
}

if (elgg_is_admin_logged_in()) {
	$entity->url = $url;
}

$entity->title = get_input('title');
$entity->description = get_input('description');
$entity->show_description = (bool) get_input('show_description');
$entity->layout = get_input('layout');

$entity->save();

$entity->setManagers((array) get_input('manager', []));

elgg_delete_system_cache('widget_pages');

return elgg_ok_response('', elgg_echo('save:success'));
