<?php
/**
 * Elgg edit widget layout
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = $vars['entity']->getGUID();

$form_body = $vars['body'];

$access_options = array('internalname' => 'params[access_id]','value' => $vars['entity']->access_id);

$access_options["options"] = trigger_plugin_hook("widget_manager:widget:access", $vars['entity']->getOwnerEntity()->getType(), array("entity" => $vars["entity"]));

$form_body .= "<p><label>" . elgg_echo('access') . ": " . elgg_view('input/access', $access_options) . "</label></p>";

$form_body .= "<a href='javascript:void(0);' onclick='$(this).next().toggle();'>" . elgg_echo("widget_manager:widgets:edit:advanced") . "</a>";
$form_body .= "<span style='display: none;'>";
$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_title') . ": " . elgg_view('input/text', array('internalname' => 'params[widget_manager_custom_title]','value' => $vars['entity']->widget_manager_custom_title)) . "</label></p>";

if($vars['entity']->context == "index" || $vars['entity']->context == "groups"){
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_url') . ": " . elgg_view('input/text', array('internalname' => 'params[widget_manager_custom_url]','value' => $vars['entity']->widget_manager_custom_url)) . "</label></p>";
}

if($vars['entity']->context == "index"){	
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:hide_header') . ": " . elgg_view('input/pulldown', array('internalname' => 'params[widget_manager_hide_header]','value' => $vars['entity']->widget_manager_hide_header, 'options_values' => array("no" => elgg_echo("option:no"),"yes" => elgg_echo("option:yes")))) . "</label></p>";
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:disable_widget_content_style') . ": " . elgg_view('input/pulldown', array('internalname' => 'params[widget_manager_disable_widget_content_style]','value' => $vars['entity']->widget_manager_disable_widget_content_style, 'options_values' => array("no" => elgg_echo("option:no"),"yes" => elgg_echo("option:yes")))) . "</label></p>";
} elseif($vars['entity']->context == "default_profile" || $vars['entity']->context == "default_dashboard"){
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:show_edit') . ": " . elgg_view('input/pulldown', array('internalname' => 'params[widget_manager_show_edit]','value' => $vars['entity']->widget_manager_show_edit, 'options_values' => array("yes" => elgg_echo("option:yes"), "no" => elgg_echo("option:no")))) . "</label></p>";
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:show_toggle') . ": " . elgg_view('input/pulldown', array('internalname' => 'params[widget_manager_show_toggle]','value' => $vars['entity']->widget_manager_show_toggle, 'options_values' => array("yes" => elgg_echo("option:yes"), "no" => elgg_echo("option:no")))) . "</label></p>";
}
$widget_context = $vars['entity']->context;

if($widget_context == "index" || $widget_context == "default_profile" || $widget_context == "default_dashboard"){
	$form_body .= "<p><label>" . elgg_echo('widget_manager:widgets:edit:custom_class') . ": " . elgg_view('input/text', array('internalname' => 'params[widget_manager_custom_class]','value' => $vars['entity']->widget_manager_custom_class)) . "</label></p>";
}

$form_body .= "</span>";

$form_body .= "<p>" . elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $guid)) . elgg_view('input/hidden', array('internalname' => 'noforward', 'value' => 'true')) . elgg_view('input/submit', array('internalname' => "submit$guid", 'value' => elgg_echo('save'))) . "</p>";

echo elgg_view('input/form', array('internalid' => "widgetform$guid", 'body' => $form_body, 'action' => "{$vars['url']}action/widgets/save"));

?>
<script type="text/javascript">
$(document).ready(function() {

	$("#widgetform<?php echo $guid; ?>").submit(function () {

		$("#submit<?php echo $guid; ?>").attr("disabled","disabled").attr("value","<?php echo elgg_echo("saving"); ?>");

		$("#widgetcontent<?php echo $guid; ?>").html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');

		$("#widget<?php echo $guid; ?> .toggle_box_edit_panel").click();

		var variables = $("#widgetform<?php echo $guid; ?>").serialize();

		$.post($("#widgetform<?php echo $guid; ?>").attr("action"),variables,function() {
			$("#submit<?php echo $guid; ?>").attr("disabled","");
			$("#submit<?php echo $guid; ?>").attr("value","<?php echo elgg_echo("save"); ?>");
			$("#widgetcontent<?php echo $guid; ?>").load("<?php echo $vars['url']; ?>pg/view/<?php echo $guid; ?>?shell=no&username=<?php echo page_owner_entity()->username; ?>&context=<?php echo get_context(); ?>&callback=true");
		});

		return false;

	});
});
</script>