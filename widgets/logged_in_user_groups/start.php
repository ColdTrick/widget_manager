<?php 
/* init file for widget */

function widget_logged_in_user_groups_init() {
	if (elgg_is_active_plugin("groups")) {
		elgg_register_widget_type('logged_in_user_groups', elgg_echo('groups:widget:membership'), elgg_echo('groups:widgets:description'), "index", true);
	}
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_logged_in_user_groups_init");
