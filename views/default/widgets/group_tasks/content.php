<?php

	$widget = $vars["entity"];
	$owner = $vars['entity']->getOwnerEntity();

    if($owner->tasks_enable != 'no'){

    	elgg_push_context("search");
    	
		$options = array(
				"type" => "object",
				"subtype" => "tasks",
				"container_guid" => $owner->getGUID(),
				"limit" => 5,
				"fullview" => false
			);
			
	    $objects = elgg_list_entities($options);
		
	    elgg_pop_context();
	    
	    if($objects){
	    	
	    	echo $objects;
			echo "<div class=\"widget_more_wrapper\"><a href=\"" . $vars['url'] . "tasks/" . $owner->username . "/items\">" . elgg_echo('more') . "</a></div>";
		} else {
			echo "<div class=\"widget_more_wrapper\"><a href=\"" . $vars['url'] . "tasks/" . $owner->username . "/add?container_guid=" . $owner->getGUID() . "\">" . elgg_echo('tasks:add') . "</a></div>";
		}
	
    }
?>