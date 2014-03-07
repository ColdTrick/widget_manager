<?php
/* init file for tagcloud widget */

function widget_tagcloud_init() {
	elgg_register_widget_type("tagcloud", elgg_echo("tagcloud"), elgg_echo("widgets:tagcloud:description"), array("profile", "dashboard", "index", "groups"), false);
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_tagcloud_init");