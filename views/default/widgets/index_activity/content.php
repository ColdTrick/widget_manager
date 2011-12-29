<?php 
	global $CONFIG;
	
	$widget = $vars["entity"];
	
	$count = sanitise_int($widget->activity_count, false);
	if(empty($count)){
		$count = 10;
	}
	
	//$activity_content = $widget->getMetadata("activity_content");
	
	$river_options = array(
			"pagination" => false,
			"limit" => $count
		);
	
	if(empty($activity_content)){
		$activity = elgg_list_river($river_options);
		//$activity = elgg_view_river_items(0, 0, "", "", "", '', $count, 0, 0, FALSE);
	} else {
		/*
		// Construct 'where' clauses for the river
		$where = array();
		// river table does not have columns expected by get_access_sql_suffix so we modify its output
		$where[] = str_replace("and enabled='yes'",'',str_replace('owner_guid','subject_guid',get_access_sql_suffix()));
		
		$content_where_filter = array();
		foreach($activity_content as $content){
			list($type, $subtype) = explode(",", $content);
			if(!empty($type)){
				$content_where = " (type = '{$type}'";
				if($subtype){
					$content_where .= " AND subtype = '{$subtype}'";
				}
				$content_where .= ")";
				$content_where_filter[] = $content_where;
			}
		}
			
		$where[] = " " . implode(' or ', $content_where_filter);
		
		$whereclause = implode(' and ', $where);
		
		// Get input from outside world and sanitise it
		$offset = (int) get_input('offset',0);
		
		$sql = "select id,type,subtype,action_type,access_id,view,subject_guid,object_guid,annotation_id,posted" .
			 		" from {$CONFIG->dbprefix}river where {$whereclause} order by posted desc limit {$offset},{$count}";
		
		// Get river items, if they exist
		if ($riveritems = get_data($sql)) {
		
			$activity = elgg_view('river/item/list',array(
				'limit' => $count,
				'offset' => $offset,
				'items' => $riveritems,
				'pagination' => false
			));
		}*/
	}
	
	if(empty($activity)){
		$activity = elgg_echo("river:none");
	}
	
	echo $activity; 
	