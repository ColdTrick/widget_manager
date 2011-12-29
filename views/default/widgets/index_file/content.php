<?php 

	// get widget settings
	$count = sanitise_int($vars["entity"]->file_count, false);
	if(empty($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "file",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false
	);
	
	if($files = elgg_list_entities($options)){
		echo $files;
	} else {
		echo elgg_echo("file:none");
	}
