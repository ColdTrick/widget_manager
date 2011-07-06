<?php 

	$widget = $vars["entity"];
	$owner = $widget->getOwnerEntity();
	
	$cloud_options = array();
	
	if($owner instanceof ElggUser){
		$cloud_options["owner_guid"] = $owner->getGUID();
	} elseif($owner instanceof ElggGroup){
		$cloud_options["container_guid"] = $owner->getGUID();
	}

	$cloud = elgg_view_tagcloud($cloud_options);

	if(empty($cloud)){
		$cloud = elgg_echo("widgets:tag_cloud:no_data");
	}
	
	echo elgg_view("page_elements/contentwrapper", array("body" => $cloud));
	
?>