<?php

elgg_set_page_owner_guid(elgg_get_site_entity()->getGUID()); // site owns the index widgets

$num_columns = 3;
$handler = get_input("handler");

$layout = elgg_get_plugin_setting("widget_layout", "widget_manager");

$extra_contexts_config = elgg_get_plugin_setting("extra_contexts_config", "widget_manager");
$extra_contexts_config = json_decode($extra_contexts_config, true);
if (is_array($extra_contexts_config)) {
	$contexts_config = elgg_extract($handler, $extra_contexts_config);
	if ($contexts_config) {
		$layout = elgg_extract("layout", $contexts_config, $layout);
	}
}

if (!empty($layout)) {
	$num_columns = count(explode("|", $layout));
}

$style = "";

switch ($layout) {
	case "33|33|33":
	case "50|50":
		break;
	default:
		$columns = array_reverse(explode("|", $layout));
		
		foreach ($columns as $index => $col_width) {
			$col_index = $index + 1;
			$style .= "#elgg-widget-col-" . $col_index . " { width: " . $col_width . "%; }";
		}
		
		// determine top row width
		break;
}

if (!empty($style)) {
	$style = "<style type='text/css'>" . $style . "</style>";
}

// draw the page
$params = array(
	'num_columns' => $num_columns,
	'exact_match' => true
);
$content = elgg_view_layout('widgets', $params);

$body = elgg_view_layout('one_column', array('content' => $style . $content));

echo elgg_view_page("", $body);
