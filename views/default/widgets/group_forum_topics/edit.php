<?php 
	$widget = $vars["entity"];
	
	$topic_count = $widget->topic_count;
	if(empty($topic_count)){
		$topic_count = 4;
	}
		
?>
<div>
	<?php echo elgg_echo("widgets:group_forum_topics:settings:topic_count"); ?>
	<?php echo elgg_view("input/dropdown", array("name" => "params[topic_count]", "options" => range(1, 10), "value" => $topic_count)); ?>
</div>