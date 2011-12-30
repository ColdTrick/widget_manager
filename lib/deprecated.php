<?php
if(!function_exists("add_widget_title_link")){
	/**
	* @deprecated 1.7.  Use widget_manager_add_widget_title_link().
	*
	* @param $handler
	* @param $link
	*/
	function add_widget_title_link($handler, $link){
		elgg_deprecated_notice("add_widget_title_link() was deprecated by widget_manager_add_widget_title_link()", "1.7");
		widget_manager_add_widget_title_link($handler, $link);
	}	
}