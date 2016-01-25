<?php

$dashboard_type_options = [
	'widgets' => elgg_echo('widget_manager:multi_dashboard:types:widgets'),
	'iframe' => elgg_echo('widget_manager:multi_dashboard:types:iframe')
];

if ($entity = elgg_extract('entity', $vars)) {
	$edit = true;
	$guid = $entity->getGUID();
	$title = $entity->title;
	
	$dashboard_type = $entity->getDashboardType();
		
	$num_columns = $entity->getNumColumns();
	
	$iframe_url = $entity->getIframeUrl();
	$iframe_height = $entity->getIframeHeight();
	
	$submit_text = elgg_echo('update');
} else {
	$edit = false;
	$title = get_input('title', '');
	$guid = null;
	
	if (!empty($title)) {
		$title = str_replace(elgg_get_site_entity()->name . ': ', '', $title);
	}
	
	$dashboard_type = 'widgets';
	
	$num_columns = 3;
	
	$iframe_url = 'http://';
	$iframe_height = 450;
	
	$submit_text = elgg_echo('save');
}

switch ($dashboard_type) {
	case 'iframe':
		$iframe_class = '';
		$widgets_class = 'hidden';
		break;
	case 'widgets':
	default:
		$iframe_class = 'hidden';
		$widgets_class = '';
		break;
}

$form_data = '<div>';
$form_data .= elgg_format_element('label', [], elgg_echo('title') . '*');
$form_data .= elgg_view('input/text', ['name' => 'title', 'value' => $title]);
$form_data .= '</div>';

$form_data .= '<div>';
$form_data .= elgg_format_element('label', [], elgg_echo('widget_manager:multi_dashboard:types:title'));
$form_data .= elgg_view('input/dropdown', [
	'name' => 'dashboard_type',
	'options_values' => $dashboard_type_options,
	'value' => $dashboard_type,
	'onchange' => 'widget_manager_change_dashboard_type(this);',
	'class' => 'mlm',
]);
$form_data .= '</div>';

$form_data .= "<div class='widget-manager-multi-dashboard-types-widgets " . $widgets_class . "'>";
$form_data .= elgg_format_element('label', [], elgg_echo('widget_manager:multi_dashboard:num_columns:title'));
$form_data .= elgg_view('input/dropdown', [
	'name' => 'num_columns',
	'options' => range(1, 6),
	'value' => $num_columns,
	'class' => 'mlm',
]);
$form_data .= '</div>';

$form_data .= "<div class='widget-manager-multi-dashboard-types-iframe " . $iframe_class . "'>";
$form_data .= elgg_format_element('label', [], elgg_echo('widget_manager:multi_dashboard:iframe_url:title'));
$form_data .= elgg_view('input/url', [
	'name' => 'iframe_url',
	'value' => $iframe_url,
]);
$form_data .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widget_manager:multi_dashboard:iframe_url:description'));
$form_data .= '</div>';

$form_data .= "<div class='widget-manager-multi-dashboard-types-iframe " . $iframe_class . "'>";
$form_data .= elgg_format_element('label', [], elgg_echo('widget_manager:multi_dashboard:iframe_height:title'));
$form_data .= elgg_view('input/text', [
	'name' => 'iframe_height',
	'value' => $iframe_height,
	'size' => '5',
	'maxlength' => '6',
	'style' => 'width: 100px;',
]) . 'px';
$form_data .= '</div>';

$form_data .= '<div class="elgg-foot">';
$form_data .= elgg_format_element('div', ['class' => 'elgg-subtext'], elgg_echo('widget_manager:multi_dashboard:required'));

$form_data .= elgg_view('input/submit', ['value' => $submit_text]);

if ($edit) {
	$form_data .= elgg_view('input/hidden', ['name' => 'guid', 'value' => $guid]);
	$form_data .= elgg_view('output/url', [
		'href' => elgg_get_site_url() . 'action/multi_dashboard/delete?guid=' . $guid,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt',
		'confirm' => true,
	]);
}

$form_data .= '</div>';

echo $form_data;
