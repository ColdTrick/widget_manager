<?php

	$widget = $vars["entity"];
	$owner = $vars['entity']->getOwnerEntity();

    if($owner->tasks_enable != 'no'){

    	$oldcontext = get_context();
    	set_context("search");
    	
		$options = array(
				"type" => "object",
				"subtype" => "tasks",
				"container_guid" => $owner->getGUID(),
				"limit" => 5,
				"fullview" => false
			);
			
	    $objects = elgg_list_entities($options);
		
	    set_context($oldcontext);
	    
	    if($objects){
	    	
	    	echo $objects;
			echo "<div class=\"widget_more_wrapper\"><a href=\"" . $vars['url'] . "pg/tasks/" . $owner->username . "/items\">" . elgg_echo('more') . "</a></div>";
		} else {
			echo "<div class=\"widget_more_wrapper\"><a href=\"" . $vars['url'] . "pg/tasks/" . $owner->username . "/add?container_guid=" . $owner->getGUID() . "\">" . elgg_echo('tasks:add') . "</a></div>";
		}
	
    }
?>