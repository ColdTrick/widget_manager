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
		if(elgg_is_active_plugin("tinymce_extended")){
			echo elgg_view("input/longtext", array("name" => "params[html_content]", "value" => $widget->html_content));
		} else {
			echo elgg_view("input/plaintext", array("name" => "params[html_content]", "value" => $widget->html_content));
		}
		echo elgg_echo("widgets:free_html:settings:use_content_wrapper");
		echo " " . elgg_view("input/dropdown", array("name" => "params[use_content_wrapper]", "value" => $widget->use_content_wrapper, "options_values" => $noyes_options));
	?>
</div>