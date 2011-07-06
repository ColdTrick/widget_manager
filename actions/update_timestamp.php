<?php 

	$type = get_input("type");
	$context = get_input("context");
	
	if(!empty($type) && !empty($context)){
		set_plugin_setting($context . "_enforce_" . $type, time(), "widget_manager");
	}

	forward(REFERER);

?>