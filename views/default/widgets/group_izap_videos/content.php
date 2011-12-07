<?php 

	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();

	$video_count = $widget->video_count;
	if(empty($video_count)){
		$video_count = 4;
	}
	
	$options = array(
		"type" => "object",
		"subtype" => "izap_videos",
		"container_guid" => $group->getGUID(),
		"limit" => $video_count,
		"pagination" => false,
		"full_view" => false
	);
	
	elgg_push_context("search");
	
	if($videos = elgg_list_entities($options)){
		echo $videos;
		
		// read more link
		echo "<div class='widget_more_wrapper'>";
		echo elgg_view("output/url", array("href" => $vars["url"] . "videos/list/" .  $group->username, "text" => elgg_echo('izap_videos:groupvideos')));
		echo "</div>";
	} else {
		echo "<div class='widget_more_wrapper'>" . elgg_echo("izap_videos:notfound") . "</div>";
	}
	
	elgg_pop_context();