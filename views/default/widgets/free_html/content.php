<?php 
	$widget = $vars["entity"];
	
	if(!empty($widget->html_content)){
		if($widget->use_content_wrapper == "yes"){
			echo $widget->html_content;
		} else {
			echo $widget->html_content;
			
		}
	} else {
		echo elgg_echo("widgets:free_html:no_content");
	}
	