<?php 

	$count = sanitise_int($vars["entity"]->file_count, false);
	if(empty($count)){
		$count = 8;
	}

?>
<div>
	<?php echo elgg_echo("widget_manager:widgets:index_file:file_count"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[file_count]", "value" => $count, "size" => "4", "maxlength" => "4")); ?>
</div>