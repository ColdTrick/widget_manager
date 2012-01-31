<?php

	function widget_manager_multi_dashboard_page_handler($page){
		$result = false;
		
		switch($page[0]){
			case "edit":
				$result = true;
				
				include(dirname(dirname(__FILE__)) . "/pages/multi_dashboard/edit.php");
				break;
		}
		
		return $result;
	}