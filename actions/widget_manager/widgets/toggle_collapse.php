<?php

$guid = (int) get_input('guid');
$collapsed = (boolean) get_input('collapsed');

$user = elgg_get_logged_in_user_entity();
$widget = get_entity($guid);

if (!($user instanceof \ElggUser) || !($widget instanceof \WidgetManagerWidget)) {
	return elgg_error_response();
}

if ($collapsed) {
	$widget->collapse();
} else {
	$widget->expand();
}

return elgg_ok_response();
