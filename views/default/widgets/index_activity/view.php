<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->activity_count;
	if(empty($count) || !is_int($count)){
		$count = 10;
	}

	if(!($activity = elgg_view_river_items (0, 0, "", "", "", "", $count, 0, 0, false))){
		$activity = elgg_echo("widget_manager:widgets:index_activity:no_results");
	}

?>
<div class="contentWrapper">
	<?php echo $activity; ?>
</div>