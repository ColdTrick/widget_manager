<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

$widget = $vars['widget'];
$show_edit = elgg_extract('show_edit', $vars, true);

$params = array(
	'text' => ' ',
	'href' => "#elgg-widget-content-$widget->guid",
	'class' => 'elgg-widget-collapse-button',
	'rel' => 'toggle',
);
$collapse_link = elgg_view('output/url', $params);

$delete_link = $edit_link = '';
if ($widget->canEdit() && (!$widget->fixed || elgg_is_admin_logged_in())) {
	$params = array(
		'text' => elgg_view_icon('delete-alt'),
		'title' => elgg_echo('widget:delete', array($widget->getTitle())),
		'href' => "action/widgets/delete?widget_guid=$widget->guid",
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg-widget-delete-button',
		'id' => "elgg-widget-delete-button-$widget->guid"
	);
	$delete_link = elgg_view('output/url', $params);

	if ($show_edit) {
		$params = array(
			'text' => elgg_view_icon('settings-alt'),
			'title' => elgg_echo('widget:edit'),
			'href' => "#widget-edit-$widget->guid",
			'class' => "elgg-widget-edit-button",
			'rel' => 'toggle',
		);
		$edit_link = elgg_view('output/url', $params);
	}
	
	if(elgg_in_context("default_widgets") && in_array($widget->context, array("profile", "dashboard")) && $widget->fixed_parent_guid){
		$class = "widget-manager-fix";
		if($widget->fixed){
			$class .= " fixed";
		}
		$params = array(
					'text' => elgg_view_icon('widget-manager-push-pin'),
					'title' => elgg_echo('widget_manager:widgets:fix'),
					'href' => "#$widget->guid",
					'class' => $class,
		);
		$fix_link = elgg_view('output/url', $params);
	}
}

echo <<<___END
	$collapse_link
	$delete_link
	$edit_link
	$fix_link
___END;
