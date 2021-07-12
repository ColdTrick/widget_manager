<?php
/**
 * add the ability to reset the access of all widgets in the group to a certain access level
 */

$group = elgg_extract('entity', $vars);

if (empty($group) || !($group instanceof ElggGroup) || !$group->canEdit()) {
	return;
}

$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
if (!in_array($group_enable, ['yes', 'forced'])) {
	return;
}

$widgets_count = elgg_count_entities([
	'type' => 'object',
	'subtype' => 'widget',
	'owner_guid' => $group->guid,
	'private_setting_name' => 'context',
	'private_setting_value' => 'groups',
]);

if (!$widgets_count) {
	// no widgets = no need for these actions
	return;
}

$access_options = get_write_access_array(0, 0, false, [
	'container_guid' => $group->guid,
	'value' => $group->access_id,
	'entity_type' => 'object',
	'entity_subtype' => 'widget',
	'purpose' => 'groups_widget_access',
]);

if (empty($access_options)) {
	return;
}

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('widget_manager:forms:groups_widget_access:description'),
]);

foreach ($access_options as $access_id => $option) {
	$content .= elgg_view('output/url', [
		'href' => elgg_http_add_url_query_elements('action/widget_manager/groups/update_widget_access', [
			'widget_access_level' => $access_id,
			'group_guid' => $group->guid,
		]),
		'text' => $option,
		'is_action' => true,
		'class' => [
			'elgg-button',
			'elgg-button-action',
		],
	]);
}

echo elgg_view_module('info', elgg_echo('widget_manager:forms:groups_widget_access:title'), $content);
