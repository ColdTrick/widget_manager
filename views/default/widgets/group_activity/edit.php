<?php

$widget = elgg_extract('entity', $vars);
/* @var ElggWidget $widget */

$count = (int) $widget->activity_count;
if (! $count) {
    $count = 10;
}

?>
<div>
	<?php echo elgg_echo("widget:numbertodisplay"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[activity_count]", "value" => $count, "size" => "4", "maxlength" => "4"));?>
</div>
