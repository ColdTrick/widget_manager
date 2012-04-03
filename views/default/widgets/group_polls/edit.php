<?php

$display_count = sanitise_int($vars["entity"]->display_count);
if(empty($display_count)){
    $display_count = 4;
}

?>
<div>
	<?php 
        echo elgg_echo("widget:numbertodisplay");
        echo elgg_view("input/dropdown", array("name" => "params[display_count]", "options" => range(1, 10), "value" => $display_count));
	?>
</div>