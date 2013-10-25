<?php 
	$widget = $vars["entity"];
	
	if(!empty($widget->html_content)){
		echo elgg_view('output/longtext',  array('value' => $widget->html_content));
	} else {
		echo elgg_echo("widgets:free_html:no_content");
	}
	
