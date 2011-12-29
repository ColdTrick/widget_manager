<?php 

	$count = sanitise_int($vars["entity"]->pages_count, false);
	if(empty($count)){
		$count = 8;
	}

?>
<div>
	<?php echo elgg_echo("widget:numbertodisplay"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[pages_count]", "value" => $count, "size" => "4", "maxlength" => "4")); ?>
</div>