<?php 
	$widget = $vars["entity"];
	
	if(!empty($widget->html_content)){
		echo $widget->html_content;
	} else {
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widgets:free_html:no_content")));
	}
?>