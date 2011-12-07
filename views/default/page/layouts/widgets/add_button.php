<?php
/**
 * Button area for showing the add widgets panel
 */

?>
<div class="elgg-widget-add-control">
<?php
	echo elgg_view('output/url', array(
		'id' => 'widgets-add-panel',
		'href' => '#widget_manager_widgets_select',
		'text' => elgg_echo('widgets:add'),
		'class' => 'elgg-button elgg-button-action',
		'is_trusted' => true,
	));
?>
</div>
