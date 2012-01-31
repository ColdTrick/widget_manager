<?php

$md_entities = elgg_extract("entities", $vars);
if($md_entities){
	
	$tabs = array();
	foreach($md_entities as $entity){
		
		$selected = false;
		if($entity->getGUID() == get_input("multi_dashboard_guid")){
			$selected = true;
		}
		$tabs[] = array(
				"text" => $entity->title,
				"href" => $entity->getURL(),
				"id" => $entity->getContext(),
				"selected" => $selected,
				
			);
	}
	
	echo elgg_view("navigation/tabs", array("id" => "widget-manager-multi-dashboard-tabs", "tabs" => $tabs));
}
