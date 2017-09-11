<?php

$plugin_setting = elgg_get_plugin_setting('group_enable', 'widget_manager');
if (!in_array($plugin_setting, ['yes', 'forced'])) {
	return elgg_error_response(elgg_echo('widget_manager:action:force_tool_widgets:error:not_enabled'));
}

$counter = 0;

$groups = elgg_get_entities([
	'type' => 'group',
	'limit' => false,
	'batch' => true,
]);
foreach ($groups as $group) {
	$group->save();
	$counter++;
}

return elgg_ok_response('', elgg_echo('widget_manager:action:force_tool_widgets:succes', [$counter]));
