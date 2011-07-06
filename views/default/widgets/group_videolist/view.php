<?php

	/**
	 * Elgg Video Plugin
	 * This plugin allows users to create a library of videos for groups
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Prateek Choudhary <synapticfield@gmail.com>
	 * @copyright Prateek Choudhary
	 */
	 
	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();
	
	//the number of files to display
	$video_count = (int) $widget->video_count;
	if (empty($video_count)){
		$video_count = 4;
	}
	
	$options = array(
		"type" => "object",
		"subtype" => "videolist",
		"container_guid" => $group->getGUID(),
		"limit" => $video_count,
		"pagination" => false,
		"full_view" => false
	);
	
	$context = get_context();
	set_context("search");
	
	//get the user's files
	//if there are some files, go get them
	if ($videos = elgg_list_entities($options)) {
		//display in list mode
		echo $videos;
		
		//get a link to the users files
		echo "<div class='forum_latest'>";
		echo elgg_view("output/url", array("href" => $vars['url'] . "pg/videolist/owned/" . $group->username, "text" => elgg_echo("videolist:groupall")));
		echo "</div>";
	} else {
		echo "<div class='widget_more_wrapper'>" . elgg_echo("videolist:none") . "</div>";
	}
	
	set_context($context);
?>