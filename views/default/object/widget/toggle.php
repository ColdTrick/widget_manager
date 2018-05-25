<?php

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \WidgetManagerWidget) {
	return;
}

if (in_array($widget->context, ['admin', 'dashboard'])) {
	return;
}

if (($widget->widget_manager_hide_header === 'yes') || !$widget->canCollapse()) {
	return;
}

echo elgg_view_menu('widget_toggle', $vars);
