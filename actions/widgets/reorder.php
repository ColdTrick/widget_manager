<?php
/**
 * Elgg widget reorder action
 *
 * @package Elgg
 * @subpackage Core
 */

gatekeeper();

global $CONFIG;

$owner = get_input('owner');
$context = get_input('context');

$widget_positions = array(
	"1" => explode('::', get_input('debugField1')),
	"2" => explode('::', get_input('debugField2')),
	"3" => explode('::', get_input('debugField3')),
	"4" => explode('::', get_input('debugField4'))
	);

$new_widgets = array();
	
foreach($widget_positions as $col => $widgets){
	if(is_array($widgets)){
		foreach($widgets as $widget_guid){
			$new_widgets[$widget_guid] = $col;		
		}
	}
}

$widget_order = array_keys($new_widgets);

$current_widgets = get_entities_from_private_setting("context", $context, "object", "widget", $owner, "", 99999, 0, false, $CONFIG->site_guid);

foreach($current_widgets as $pos => $db_widget){
	$guid = $db_widget->getGUID();
	$target_column = $new_widgets[$guid];
	
	if(!empty($target_column)){
		$pos = array_search($guid, $widget_order);
		$db_widget->column = $target_column;
		$db_widget->order = ($pos + 1) * 10;
		
	} else {
		// widget delete
		$db_widget->delete();
	} 
	
}

exit();