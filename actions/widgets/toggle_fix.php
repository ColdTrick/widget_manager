<?php 
	
	$entity_guid = get_input("guid");
	if($entity = get_entity($entity_guid)){
		if($entity instanceof ElggWidget){
			$current = $entity->fixed;
			var_dump($current);
			if($current){
				$entity->fixed = false;
			} else {
				$entity->fixed = true;
			}
		}		
	} 
	exit();
	