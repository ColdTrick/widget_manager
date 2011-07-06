<?php
	
	if(isloggedin()){
	
		$owner_guid = (int) get_input('owner_guid');
		$handler = get_input('widget_handler');
		$context = get_input('context');
		$column = (int) get_input('column');
		
		set_context($context);
		
		elgg_view_title("dummy"); // trigger page setup
				
		$result = false;
		
		if (!empty($owner_guid)) {
			
			if ($owner = get_entity($owner_guid)) {
				
				set_page_owner($owner_guid); // needed for widget draw that needs page_owner
				if ($owner->canEdit()) {
					if($owner instanceof ElggGroup){
						$access_id = $owner->group_acl;
					}
					$result = add_widget($owner->getGUID(),$handler,$context,0,$column, $access_id);
				}	
			}	
		}
		
		if($result){
			$widgets = get_widgets($owner_guid, $context, $column);
			$firstKey = array_keys($widgets);
			$firstKey = $firstKey[0];
			
			echo display_widget($widgets[$firstKey]);
		} 
	}
?>