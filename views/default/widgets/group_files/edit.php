<?php 
	$widget = $vars["entity"];
	
	$file_count = $widget->file_count;
	if(empty($file_count)){
		$file_count = 4;
	}
?>
<div>
	<?php 
		echo elgg_echo("widgets:group_files:settings:file_count"); 
		echo elgg_view("input/dropdown", array("name" => "params[file_count]", "options" => range(1, 10), "value" => $file_count));
	?>
</div>