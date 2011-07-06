<?php 
	$widget = $vars["entity"];
	
?>
<div>
	<?php 
		echo elgg_echo("widgets:free_html:settings:html_content"); 
		echo elgg_view("input/plaintext", array("internalname" => "params[html_content]", "value" => $widget->html_content));
	?>
</div>