<?php

	$guid = (int) get_input("guid");
	
	if(!empty($guid)){
		if(($entity = get_entity($guid)) && $entity->canEdit()){
			if(elgg_instanceof($entity, "object", MultiDashboard::SUBTYPE)){
				$title = $entity->title;
				
				if($entity->delete()){
					system_message(elgg_echo("entity:delete:success", array($title)));
				} else {
					register_error(elgg_echo("entity:delete:fail", array($title)));
				}
			} else {
				register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($guid, MultiDashboard::SUBTYPE)));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward("dashboard");