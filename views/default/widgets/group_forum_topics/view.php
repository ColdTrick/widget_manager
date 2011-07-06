<?php
	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();
	
	if($group->forum_enable != "no"){
		
		$topic_count = (int) $widget->topic_count;
		if($topic_count < 1){
			$topic_count = 4;
		}
		
		$forum_options = array(
			'types' => 'object', 
			'subtypes' => 'groupforumtopic', 
			'annotation_names' => 'group_topic_post', 
			'container_guid' => $group->getGUID(), 
			'limit' => $topic_count, 
			'order_by' => 'maxtime desc'
		);
		
	    if($forum = elgg_get_entities_from_annotations($forum_options)){
	        foreach($forum as $f){
	        	    
                $count_annotations = $f->countAnnotations("group_topic_post");
                
        	    $icon = elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'small', 'override' => true));
    	        $body = "<div class=\"topic_title\"><p><a href=\"{$f->getURL()}\">" . $f->title . "</a></p> <p class=\"topic_replies\"><small>".elgg_echo('groups:posts').": " . $count_annotations . "</small></p></div>";
    	        
    	        echo elgg_view_listing($icon, $body);
	        }
	    } else {
	    	echo "<div class='contentWrapper'>";
			echo elgg_echo("grouptopic:notcreated");
			echo "</div>";
	    }
		
		echo "<div class='clearfloat'></div>";
	
	} 
?>