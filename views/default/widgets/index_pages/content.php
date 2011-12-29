<?php 

	$count = sanitise_int($vars["entity"]->pages_count, false);
	if(empty($count)){
		$count = 8;
	}

	$options = array(
		"type" => "object",
		"subtype" => "page_top",
		"limit" => $count,
		"full_view" => false,
		"pagination" => false
	);
	
	if(!($result = elgg_list_entities($options))){
		$result =  elgg_echo("pages:none");
	}

	echo $result;
	