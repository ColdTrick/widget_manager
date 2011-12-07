<?php 
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = $vars['widget'];
$show_access = elgg_extract('show_access', $vars, true);

$edit_view = "widgets/$widget->handler/edit";
$custom_form_section = elgg_view($edit_view, array('entity' => $widget));

$access = '';
if ($show_access) {
	$access = elgg_echo('access') . ': ' . elgg_view('input/access', array(
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
	));
}

$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no")
);

$noyes_options = array_reverse($yesno_options, true);

$advanced = "<a rel='toggle' href='#widget-manager-widget-edit-advanced-" . $widget->getGUID() . "'>" . elgg_echo("widget_manager:widgets:edit:advanced") . "</a>";
$advanced .= "<div class='hidden' id='widget-manager-widget-edit-advanced-" . $widget->getGUID() . "'>";
$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_title') . ": " . elgg_view('input/text', array('name' => 'params[widget_manager_custom_title]','value' => $widget->widget_manager_custom_title)) . "</label></p>";

if($widget->context == "index" || $widget->context == "groups"){
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_url') . ": " . elgg_view('input/text', array('name' => 'params[widget_manager_custom_url]','value' => $widget->widget_manager_custom_url)) . "</label></p>";
}

if($widget->context == "index"){
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:hide_header') . ": " . elgg_view('input/dropdown', array('name' => 'params[widget_manager_hide_header]','value' => $widget->widget_manager_hide_header, 'options_values' =>$noyes_options)) . "</label></p>";
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:disable_widget_content_style') . ": " . elgg_view('input/dropdown', array('name' => 'params[widget_manager_disable_widget_content_style]','value' => $widget->widget_manager_disable_widget_content_style, 'options_values' => $noyes_options)) . "</label></p>";
} elseif($widget->context == "default_profile" || $widget->context == "default_dashboard"){
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:show_edit') . ": " . elgg_view('input/dropdown', array('name' => 'params[widget_manager_show_edit]','value' => $widget->widget_manager_show_edit, 'options_values' => $yesno_options)) . "</label></p>";
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:show_toggle') . ": " . elgg_view('input/dropdown', array('name' => 'params[widget_manager_show_toggle]','value' => $widget->widget_manager_show_toggle, 'options_values' => $yesno_options)) . "</label></p>";
}

$widget_context = $widget->context;

if($widget_context == "index" || $widget_context == "default_profile" || $widget_context == "default_dashboard"){
	$advanced .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_class') . ": " . elgg_view('input/text', array('name' => 'params[widget_manager_custom_class]','value' => $widget->widget_manager_custom_class)) . "</label></p>";
}

$advanced .= "</div>";

$hidden = elgg_view('input/hidden', array('name' => 'guid', 'value' => $widget->guid));
$submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

$body = <<<___END
	$custom_form_section
	<div>
		$access
	</div>
	<div>
		$advanced
	</div>
	<div class="elgg-foot">
		$hidden
		$submit
	</div>
___END;

echo $body;
