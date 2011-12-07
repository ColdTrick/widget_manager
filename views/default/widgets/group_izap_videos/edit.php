<?php 
	$widget = $vars["entity"];
	
	$video_count = $widget->video_count;
	if(empty($video_count)){
		$video_count = 4;
	}
	
?>
<div>
	<?php 
		echo elgg_echo("izap_videos:numbertodisplay"); 
		echo elgg_view("input/dropdown", array("name" => "params[video_count]", "options" => range(1, 10), "value" => $video_count));
	?>
</div>