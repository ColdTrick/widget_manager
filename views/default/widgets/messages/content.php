<?php
	
	
	if(!elgg_is_logged_in()){
		echo elgg_echo("widgets:messages:not_logged_in");	
	} else {
		$widget = $vars["entity"];
		
		$max_messages = (int) $widget->max_messages;
		if(empty($max_messages)){
			$max_messages = 5;
		}
		
		$user_id = elgg_get_logged_in_user_guid();
		
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
			if($widget->only_unread != "no"){
				// no unread
				echo elgg_echo("messages:nomessages");
			} else {
				// empty inbox
				echo elgg_echo("messages:nomessages");		
			}
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
		echo "<div class='widget_more_wrapper'><a href='" . $vars["url"] . "messages/compose'>" . elgg_echo("messages:compose") . "</a></div>";
	}
	