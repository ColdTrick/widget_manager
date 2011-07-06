<?php 
	$widget = $vars["entity"];
	
	$album_count = (int) $widget->album_count;
	if(empty($album_count)){
		$album_count = 4;
	}
	
?>
<div>
	<?php 
		echo elgg_echo("tidypics:widget:num_albums"); 
		echo elgg_view("input/pulldown", array("internalname" => "params[album_count]", "options" => range(1, 10), "value" => $album_count));
	?>
</div>