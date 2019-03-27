<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = elgg_extract('widget', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$show_access = elgg_extract('show_access', $vars, true);

$custom_form_section = elgg_view("widgets/$widget->handler/edit", ['entity' => $widget]);

$access = '';
if ($show_access) {
	$access = elgg_view_field([
		'#type' => 'access',
		'#label' => elgg_echo('access'),
		'name' => 'params[access_id]',
		'value' => $widget->access_id,
	]);
}

$basic_content = $custom_form_section . $access;

$advanced_content = elgg_view('forms/widgets/advanced', [
	'entity' => $widget,
	'widget_context' => $widget->context,
]);

if (empty($advanced_content)) {
	echo $basic_content;
} elseif (empty($basic_content)) {
	echo $advanced_content;
} else {
	echo elgg_view('page/components/tabs', [
		'class' => 'widget-settings',
		'tabs' => [
			[
				'text' => elgg_echo('settings'),
				'content' => $basic_content,
				'selected' => true,
			],
			[
				'text' => elgg_echo('widget_manager:widgets:edit:advanced'),
				'content' => $advanced_content,
			],
		],
	]);
	
	elgg_require_js('forms/widgets/advanced');
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $widget->guid,
]);

if (elgg_in_context('default_widgets')) {
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'default_widgets',
		'value' => 1,
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
