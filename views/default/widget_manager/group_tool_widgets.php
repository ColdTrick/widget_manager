<?php
/**
 * Prepends the widget add panel to unregister widgets based on a group tool option
 * when in the context of a group
 */

$context = elgg_extract('context', $vars);
if ($context !== 'groups') {
	// only cleanup for groups
	return;
}

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof \ElggGroup) {
	// we need a group
	return;
}

$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
if (!in_array($group_enable, ['yes', 'forced'])) {
	return;
}

$result = ['enable' => [], 'disable' => []];
$params = ['entity' => $page_owner];

$result = (array) elgg_trigger_event_results('group_tool_widgets', 'widget_manager', $params, $result);

if (empty($result)) {
	return;
}

$disable_widget_handlers = (array) elgg_extract('disable', $result, []);
foreach ($disable_widget_handlers as $handler) {
	elgg_unregister_widget_type($handler);
}
