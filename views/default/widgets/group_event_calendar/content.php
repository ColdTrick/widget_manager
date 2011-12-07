<?php 
	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();

	$event_count = $widget->event_count;
	if(empty($event_count)){
		$event_count = 4;
	}
	
	// Get the upcoming events
	$start_date = time(); // now
	$end_date = $start_date + 60*60*24*365*2; // maximum is two years from now
	
	// If there are any events to view, view them
	if($events = event_calendar_get_events_between($start_date, $end_date, false, $event_count, 0, $group->getGUID())) {
		foreach($events as $event) {
			echo elgg_view("object/event_calendar", array('entity' => $event));
		}
		
		// read more link
		echo "<div class='widget_more_wrapper'>";
		echo elgg_view("output/url", array("href" => $vars["url"] . "event_calendar/group/" . $group->getGUID(), "text" => elgg_echo('event_calendar:site_wide_link')));
		echo "</div>";
	} else {
		echo elgg_echo("event_calendar:no_events_found");
	}
