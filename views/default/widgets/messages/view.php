<?php
	
	
	if(!isloggedin()){
		echo "<div class='contentWrapper'>";
		echo elgg_echo("widgets:messages:not_logged_in");	
		echo "</div>";
	} else {
		$widget = $vars["entity"];
		
		$max_messages = (int) $widget->max_messages;
		if(empty($max_messages)){
			$max_messages = 5;
		}
		
		$user_id = get_loggedin_userid();
		
		$options = array(
			'type' => 'object',
			'subtype' => 'messages',
			'metadata_name' => 'toId',
			'metadata_value' => $user_id,
			'owner_guid' => $user_id,
			'limit' => $max_messages 
			);
		
		if($widget->only_unread != "no"){
			$options["metadata_name_value_pairs"] = array("readYet" => 0);
		}
		
		$entities = elgg_get_entities_from_metadata($options);
		
		if(empty($entities)){
			echo "<div class='contentWrapper'>";
	
			if($widget->only_unread != "no"){
				// no unread
				echo elgg_echo("messages:nomessages");
			} else {
				// empty inbox
				echo elgg_echo("messages:nomessages");		
			}
			
			echo "</div>";
		} else {
			
			foreach($entities as $entity){
				
				$from = get_entity($entity->fromId);
				if($from){
					$icon = elgg_view("profile/icon", array('entity' => $from));
				}
				$body = "<h3><a href='" . $entity->getURL() . "'>" .$entity->title . "</a></h3>";
				$body .= elgg_get_excerpt($entity->description);

				$class = "";
				if(!$entity->readYet){
					$class = " class='widgets_messages_unread'";
				}
				
				echo "<div{$class}>";
				echo elgg_view_listing($icon, $body);
				echo "</div>"; 
			}
			
		}
		echo "<div class='widget_more_wrapper'><a href='" . $vars["url"] . "pg/messages/compose'>" . elgg_echo("messages:compose") . "</a></div>";
	}
	