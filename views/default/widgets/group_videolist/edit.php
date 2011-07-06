<?php 
	$widget = $vars["entity"];
	
	$video_count = (int) $widget->video_count;
	if(empty($video_count)){
		$video_count = 4;
	}
	
?>
<div>
	<?php 
		echo elgg_echo("videolist:num_videos");
		echo elgg_view("input/pulldown", array("internalname" => "params[video_count]", "options" => range(1,10), "value" => $video_count));
	?>
</div>