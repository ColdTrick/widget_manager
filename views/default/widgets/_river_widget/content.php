<?php
/**
 * View the widget
 *
 * @package ElggRiverDash
 */
global $CONFIG;

$owner = elgg_get_page_owner_entity();

//get the type - mine or friends
$content_type = $vars['entity']->content_type;

if (!$content_type) {
	$content_type = "mine";
}

$river_owner = 0;
$river_content_type = "";

//based on type grab the correct content type
if ($content_type == "mine") {
	$river_owner = $owner->getGuid();
} elseif($content_type == "friends") {
	$river_owner = $owner->getGuid();
	$river_content_type = 'friend';
}

//get the number of items to display
$limit = (int) $vars['entity']->num_display;
if (empty($limit)) {
	$limit = 5;
}

$activity_content = $vars["entity"]->getMetadata("activity_content");
if(empty($activity_content)){
	$river = elgg_view_river_items($river_owner, 0, $river_content_type, "", "", '', $limit, 0, 0, FALSE);
} else {
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
		
	if (empty($river_content_type)) {
		if (!empty($river_owner)) {
			$where[] = " subject_guid = {$river_owner} ";
		}
	} else {
		
		if ($entities = elgg_get_entities_from_relationship(array(
			'relationship' => $river_content_type,
			'relationship_guid' => $river_owner,
			'limit' => 9999))) {
				$guids = array();
				foreach($entities as $entity) {
					$guids[] = (int) $entity->guid;
				}
				$where[] = " subject_guid in (" . implode(',',$guids) . ") ";
		} else {
			$river = elgg_echo("notfound");
		}
	}

	if(empty($river)){
		
		$whereclause = implode(' and ', $where);
		
		// Get input from outside world and sanitise it
		$offset = (int) get_input('offset',0);
		
		$sql = "select id,type,subtype,action_type,access_id,view,subject_guid,object_guid,annotation_id,posted" .
			 		" from {$CONFIG->dbprefix}river where {$whereclause} order by posted desc limit {$offset},{$limit}";
		
		// Get river items, if they exist
		if ($riveritems = get_data($sql)) {
		
			$river = elgg_view('river/item/list',array(
				'limit' => $limit,
				'offset' => $offset,
				'items' => $riveritems,
				'pagination' => false
			));
		}
	}
}

//grab the river
if(empty($river)){
	$river = elgg_echo("notfound");
}

//display
echo $river;

