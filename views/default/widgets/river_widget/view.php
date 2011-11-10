<?php
/**
 * View the widget
 *
 * @package ElggRiverDash
 */

$owner = page_owner_entity();

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
$limit = $vars['entity']->num_display;
if (!$limit) {
	$limit = 5;
}

$type = "";
$subtype = "";

if(!empty( $vars['entity']->activity_content)){
	list($type, $subtype) = explode(",",  $vars['entity']->activity_content);
}
if($type == "all"){
	$type = "";
	$subtype = "";	
}

//grab the river
$river = elgg_view_river_items($river_owner, 0, $river_content_type, $type, $subtype, '', $limit, 0, 0, FALSE);
if(empty($river)){
	$river = elgg_echo("notfound");
}
//display
echo "<div class=\"contentWrapper\">";
echo $river;
echo "</div>";
