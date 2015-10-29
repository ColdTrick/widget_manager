<?php
/**
 * Button area for showing the add widgets panel
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');


$options = array(
	'id' => 'widgets-add-panel',
	'text' => elgg_echo('widgets:add'),
	'link_class' => 'elgg-button elgg-button-action elgg-lightbox',
	'href' => '#',
	'data-colorbox-opts' => '{"inline":true, "href":"#widget_manager_widgets_select", "innerWidth": 600, "maxHeight": "80%"}'
);

if (elgg_in_context("iframe_dashboard")) {
	// TODO: why hide? we could also not output the button
	$options["style"] = "visibility: hidden;";
}

$options['name'] = 'widgets:add';

elgg_register_menu_item('title', $options);
