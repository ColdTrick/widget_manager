<?php

	$max_messages = (int) $vars["entity"]->max_messages;
	if(empty($max_messages)){
		$max_messages = 5;
	}

	echo "<div>";
	echo elgg_echo("widgets:messages:settings:max_messages") . " "; 
	echo elgg_view("input/text", array("internalname" => "params[max_messages]", "value" => $max_messages));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("widgets:messages:settings:only_unread"); 
	echo elgg_view('input/pulldown', array('internalname' => 'params[only_unread]','value' => $vars['entity']->only_unread, 'options_values' => array("yes" => elgg_echo("option:yes"), "no" => elgg_echo("option:no")))); 
	echo "</div>";
	