<?php
/* init file for likes widget */

function widget_likes_init(){
// 	elgg_register_widget_type("likes", elgg_echo("widgets:likes:title"), elgg_echo("widgets:likes:description"), "index,groups,profile,dashboard", true);
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_likes_init");