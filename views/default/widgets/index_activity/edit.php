<?php

$widget = elgg_extract('entity', $vars);

$count = sanitise_int($widget->activity_count, false);
if (empty($count)) {
	$count = 10;
}

$contents = [];

$registered_entities = elgg_get_config('registered_entities');

if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $ar) {
		if (count($registered_entities[$type])) {
			foreach ($registered_entities[$type] as $subtype) {
				$keyname = 'item:' . $type . ':' . $subtype;
				$contents[elgg_echo($keyname)] = "{$type},{$subtype}";
			}
		} else {
			$keyname = 'item:' . $type;
			$contents[elgg_echo($keyname)] = "{$type},";
		}
	}
}

$content = elgg_echo('widget:numbertodisplay') . '<br />';
$content .= elgg_view('input/text', [
	'name' => 'params[activity_count]',
	'value' => $count,
	'size' => 4,
	'maxlength' => 4,
]);

echo elgg_format_element('div', [], $content);

$content = elgg_echo('filter') . '<br />';
$content .= elgg_view('input/checkboxes', [
	'name' => 'params[activity_content]',
	'value' => $widget->activity_content,
	'options' => $contents,
]);

echo elgg_format_element('div', [], $content);
