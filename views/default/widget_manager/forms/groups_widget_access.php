<?php
/**
 * add the ability to reset the access of all widgets in the group to a certain access level
 */

$group = elgg_extract("entity", $vars);

if (empty($group) || !($group instanceof ElggGroup) || !$group->canEdit()) {
	return;
}

// no widgets = no need for this form
$options = [
	'type' => 'object',
	'subtype' => 'widget',
	'owner_guid' => $group->getGUID(),
	'private_setting_name' => 'context',
	'private_setting_value' => 'groups',
	'count' => true
];

$widgets_count = elgg_get_entities_from_private_settings($options);
if (!$widgets_count) {
	return;
}

$title = elgg_echo("widget_manager:forms:groups_widget_access:title");

$form_body = "<div>" . elgg_echo("widget_manager:forms:groups_widget_access:description") . "</div>";

$form_body .= '<div>' . elgg_view("input/access", array("name" => "widget_access_level")) . '</div>';

$form_body .= "<div class='elgg-footer'>";
$form_body .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
$form_body .= "</div>";

$content = elgg_view("input/form", array("action" => "action/widget_manager/groups/update_widget_access", "body" => $form_body));

echo elgg_view_module("info", $title, $content);