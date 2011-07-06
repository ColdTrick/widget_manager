<?php

	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();

	$number = $widget->file_count;
	if(empty($number)){
		$number = 4;
	}

	//get the group's files
	$options = array(
		"type" => "object",
		"subtype" => "file",
		"container_guid" => $group->getGUID(),
		"limit" => $number,
		"pagination" => false,
		"full_view" => false,
		"view_type_toggle" => false
	);

	$context = get_context();
	set_context("search");
	//if there are some files, go get them
	if ($files = elgg_list_entities($options)) {
		//display in list mode
		echo $files;
		
		// read more link
		echo "<div class='widget_more_wrapper'>";
		echo elgg_view("output/url", array("href" => $vars["url"] . "pg/file/owner/" .  $group->username, "text" => elgg_echo('file:more')));
		echo "</div>";
	} else {
		echo "<div class='widget_more_wrapper'>" . elgg_echo("file:none") . "</div>";
	}
	
	set_context($context);
?>