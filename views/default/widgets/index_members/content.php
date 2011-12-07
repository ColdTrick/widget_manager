<?php 

	$widget = $vars["entity"];

	$count = (int) $widget->member_count;
	$icon = $widget->user_icon;

	if(empty($count) || !is_int($count)){
		$count = 8;
	}

	$options = array(
		"type" => "user",
		"limit" => $count,
		"relationship" => "member_of_site",
		"relationship_guid" => $vars["config"]->site_guid,
		"inverse_relationship" => true,
		"full_view" => false,
		"pagination" => false
	);
	
	if($icon == "yes"){
		$options["metadata_name"] = "icontime";
	}
	
	if($users = elgg_get_entities_from_relationship($options)){
		echo "<div class='widget_manager_widget_index_members'>";
		
		foreach($users as $user){
			echo elgg_view("profile/icon", array("entity" => $user, "size" => "small"));
		}
		
		echo "</div>";
	} else {
		echo elgg_echo("widget_manager:widgets:index_members:no_result");
	}
	