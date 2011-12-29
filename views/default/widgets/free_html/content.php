<?php 
	$widget = $vars["entity"];
	
	if(!empty($widget->html_content)){
		echo $widget->html_content;
	} else {
		echo elgg_echo("widgets:free_html:no_content");
	}
	