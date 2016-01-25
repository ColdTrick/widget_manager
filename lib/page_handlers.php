<?php

/**
 * Page handlers for widget manager
 */

/**
 * Handles the extra contexts page
 *
 * @param array  $page    page elements
 * @param string $handler handler of the current page
 *
 * @return boolean
 */
function widget_manager_extra_contexts_page_handler($page, $handler) {
	
	$extra_contexts = elgg_get_plugin_setting('extra_contexts', 'widget_manager');
	if (!widget_manager_is_extra_context($handler)) {
		return false;
	}

	echo elgg_view_resource('widget_manager/extra_contexts', ['handler' => $handler]);
	return true;
}

/**
 * Function to take over the index page
 *
 * @param array  $page    page elements
 * @param string $handler handler of the current page
 *
 * @return boolean
 */
function widget_manager_index_page_handler($page, $handler) {
	echo elgg_view_resource('widget_manager/custom_index');
	return true;
}
