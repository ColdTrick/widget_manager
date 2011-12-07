<?php 

	$widget = $vars["entity"];
	$owner = $widget->getOwnerEntity();
	
	$cloud_options = array();
	
	if($owner instanceof ElggUser){
		$cloud_options["owner_guid"] = $owner->getGUID();
	} elseif($owner instanceof ElggGroup){
		$cloud_options["container_guid"] = $owner->getGUID();
	}

	$cloud_data = elgg_get_tags($cloud_options);
	
	if($cloud_data){
				
		$cloud = "";
		$max = 0;
		
        foreach($cloud_data as $tag) {
        	if ($tag->total > $max) {
        		$max = $tag->total;
        	}
        }
        
		shuffle($cloud_data);
		
		foreach($cloud_data as $tag) {	
			$count = $tag->total;
			$keyword = $tag->tag;
			
            if (!empty($cloud)) $cloud .= ", ";
            
            // range from 50% to 150%
			$size = floor(100 * ($count/($max + .0001)) + 50);
            
			if($size > 125){
				$class = "tagcloud_size_100";
			} elseif($size > 100){
				$class = "tagcloud_size_75";
			} elseif($size > 75){
				$class = "tagcloud_size_50";
			} else {
				$class = "tagcloud_size_25";
			}
			
            $cloud .= "<a class='". $class . "' href=\"" . $vars['url'] . "search/?tag=". $keyword . "\" style=\"font-size: {$size}%; text-decoration:none;\" title=\"".addslashes($keyword)." ({$count})\">" . urldecode($keyword) . "</a>";
        }
	}

	if(empty($cloud)){
		$cloud = elgg_echo("widgets:tagcloud:no_data");
	}
	
	echo $cloud;
	