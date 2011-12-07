<?php 
	$widget = $vars["entity"];
	
	$event_count = $entity->event_count;
	if(empty($event_count)){
		$event_count = 4;
	}
	
?>
<div>
	<?php 
		echo elgg_echo("event_calendar:num_display"); 
		echo elgg_view("input/dropdown", array("name" => "params[event_count]", "options" => range(1, 10), "value" => $event_count));
	?>
</div>