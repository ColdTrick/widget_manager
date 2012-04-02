<?php
$widget = $vars["entity"];

$discussion_count = $widget->discussion_count;
if(empty($discussion_count )){
	$discussion_count = 5;
}

$options = array(
	"type" => "object",
	"subtype" => "groupforumtopic",
	"limit" => $discussion_count,
	"pagination" => false
); 

if($widget->group_only == "yes"){
	$owner =  $widget->getOwnerEntity();
	$groups = $owner->getGroups("", false);

	if(!empty($groups)){
		
		$group_guids = array();
		foreach($groups as $group){
			$groups_guids[] = $group->getGUID();
		} 	
		$options["container_guids"] = $groups_guids;
	}
}

echo elgg_list_entities($options);