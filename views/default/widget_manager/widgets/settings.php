<?php

$widget_guid = get_input("guid");
$show_access = (boolean) get_input("show_access", false);
if (!$widget_guid) {
	return;
}

$widget = get_entity($widget_guid);
if (!elgg_instanceof($widget, "object", "widget") || !$widget->canEdit()) {
	return;
}

$additional_class = preg_replace('/[^a-z0-9-]/i', '-', "elgg-form-widgets-save-{$widget->handler}");

$body_vars = array(
	"widget" => $widget,
	"show_access" => $show_access
);

$form_vars = array(
	"class" => "elgg-form-widgets-save $additional_class",
);

echo "<div class='widget-manager-lightbox-edit' id='widget-edit-" . $widget->guid . "'>";
echo elgg_view_form("widgets/save", $form_vars, $body_vars);
echo "</div>";
