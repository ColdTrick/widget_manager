<?php

$guid = (int) get_input('guid');

if (empty($guid)) {
	register_error(elgg_echo('InvalidParameterException:MissingParameter'));
	forward('dashboard');
}

$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', MultiDashboard::SUBTYPE)) {
	register_error(elgg_echo('InvalidClassException:NotValidElggStar', [$guid, MultiDashboard::SUBTYPE]));
	forward('dashboard');
}

if (!$entity->canEdit()) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward('dashboard');
}

$title = $entity->title;

if ($entity->delete()) {
	system_message(elgg_echo('widget_manager:actions:multi_dashboard:delete:success', [$title]));
} else {
	register_error(elgg_echo('widget_manager:actions:multi_dashboard:delete:error:delete', [$title]));
}

forward('dashboard');
