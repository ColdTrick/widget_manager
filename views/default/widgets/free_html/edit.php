<?php 
	$widget = $vars["entity"];
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")		
	);
	
?>
<div>
	<?php 
		echo elgg_echo("widgets:free_html:settings:html_content"); 
		if(is_plugin_enabled("tinymce_extended")){
			echo elgg_view("input/longtext", array("internalname" => "params[html_content]", "value" => $widget->html_content));
		} else {
			echo elgg_view("input/plaintext", array("internalname" => "params[html_content]", "value" => $widget->html_content));
		}
		echo elgg_echo("widgets:free_html:settings:use_content_wrapper");
		echo " " . elgg_view("input/pulldown", array("internalname" => "params[use_content_wrapper]", "value" => $widget->use_content_wrapper, "options_values" => $noyes_options));
	?>
</div>