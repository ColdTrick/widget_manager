<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->activity_count;
	if(empty($count) || !is_int($count)){
		$count = 10;
	}
	
	$type = "";
	$subtype = "";
	
	if(!empty($widget->activity_content)){
		list($type, $subtype) = explode(",", $widget->activity_content);
	}
	if($type == "all"){
		$type = "";
		$subtype = "";	
	}
	
	if(!($activity = elgg_view_river_items (0, 0, "", $type, $subtype, "", $count, 0, 0, false))){
		$activity = elgg_echo("widget_manager:widgets:index_activity:no_results");
	}

?>
<div class="widget_more_wrapper">
	<?php echo $activity; ?>
</div>