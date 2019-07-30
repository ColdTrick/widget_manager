<?php
/**
 * Prepends the widget add panel to unregister widgets based on a group tool option
 * when in the context of a group
 */

$context = elgg_extract('context', $vars);
if ($context != 'groups') {
	// only cleanup for groups
	return;
}

$page_owner = elgg_get_page_owner_entity();
if (empty($page_owner) || !$page_owner instanceof \ElggGroup) {
	// we need a group
	return;
}

$result = ['enable' => [], 'disable' => []];
$params = ['entity' => $page_owner];

$result = elgg_trigger_plugin_hook('group_tool_widgets', 'widget_manager', $params, $result);

if (empty($result) || !is_array($result)) {
	return;
}

$disable_widget_handlers = elgg_extract('disable', $result);
if (!empty($disable_widget_handlers) && is_array($disable_widget_handlers)) {
		
	foreach ($disable_widget_handlers as $handler) {
		elgg_unregister_widget_type($handler);
	}
}