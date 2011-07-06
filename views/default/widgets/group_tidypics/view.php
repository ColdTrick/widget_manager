<?php 

	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();

	$album_count = (int) $widget->album_count;
	if(empty($album_count)){
		$album_count = 4;
	}
	
?>
<div class="contentWrapper">
	<?php echo elgg_view('tidypics/albums', array('num_albums' => $album_count)); ?>
</div>