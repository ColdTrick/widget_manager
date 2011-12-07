<?php 
	global $CONFIG;
	
	$entity = $vars["entity"];
	
	$rss_count = $entity->rss_count;
	if(!is_numeric($rss_count)){
		$rss_count = 4;
	}
	
	$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no")
	);
	
	$post_date_options = array(
		"friendly" => elgg_echo("widgets:rss:settings:post_date:option:friendly"),
		"date" => elgg_echo("widgets:rss:settings:post_date:option:date"),
		"no" => elgg_echo("option:no")
	);
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:rssfeed") . " "; 
	echo elgg_view("input/text", array("name" => "params[rssfeed]", "value" => $entity->rssfeed));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:rss_count") . " "; 
	echo elgg_view("input/dropdown", array("name" => "params[rss_count]", "options" => range(1,10), "value" => $rss_count)); 
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:show_feed_title") . " "; 
	echo elgg_view("input/dropdown", array("name" => "params[show_feed_title]", "options_values" => $yesno_options, "value" => $entity->show_feed_title));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:excerpt") . " "; 
	echo elgg_view("input/dropdown", array("name" => "params[excerpt]", "options_values" => $yesno_options, "value" => $entity->excerpt));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:show_item_icon") . " "; 
	echo elgg_view("input/dropdown", array("name" => "params[show_item_icon]", "options_values" => array_reverse($yesno_options), "value" => $entity->show_item_icon));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:rss:settings:post_date") . " "; 
	echo elgg_view("input/dropdown", array("name" => "params[post_date]", "options_values" => $post_date_options, "value" => $entity->post_date));
	echo "</div>";
	