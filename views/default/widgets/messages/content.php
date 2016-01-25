<?php

if (!elgg_is_logged_in()) {
	echo elgg_echo('widgets:messages:not_logged_in');
	return;
}

$widget = elgg_extract('entity', $vars);
	
$max_messages = sanitise_int($widget->max_messages, false);
if (empty($max_messages)) {
	$max_messages = 5;
}

$options = [
	'type' => 'object',
	'subtype' => 'messages',
	'metadata_name_value_pairs' => ['toId' => elgg_get_logged_in_user_guid()],
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'full_view' => false,
	'limit' => $max_messages,
];

if ($widget->only_unread != 'no') {
	$options['metadata_name_value_pairs']['readYet'] = 0;
}

$list = '';
$messages = elgg_get_entities_from_metadata($options);
if ($messages) {
	
	foreach ($messages as $message) {
		$icon = '';
		$user = get_user($message->fromId);
		if (!empty($user)) {
			$icon = elgg_view_entity_icon($user, 'tiny');
		}

		if ($message->readYet) {
			$class = 'message read';
		} else {
			$class = 'message unread';
		}
		
		$body = elgg_format_element('div', [], elgg_view('output/url', [
			'href' => $message->getURL(),
			'text' => $message->title,
			'is_trusted' => true,
		]));
		$body .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_view_friendly_time($message->time_created));
		
		$list .= elgg_format_element('li', [], elgg_view_image_block($icon, $body, ['class' => $class]));
	}

	$list = elgg_format_element('ul', ['class' => 'elgg-list'], $list);
}

if (!empty($list)) {
	echo $list;
} else {
	echo elgg_echo('messages:nomessages');
}

echo elgg_format_element('div', ['class' => 'elgg-widget-more'], elgg_view('output/url', [
	'href' => 'messages/compose',
	'text' => elgg_echo('messages:add'),
]));
