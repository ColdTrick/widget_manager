<?php 
/* init file for group_forum_topics widget */

function widget_group_forum_topics_pagesetup(){
	$page_owner = elgg_get_page_owner_entity();
	if(($page_owner instanceof ElggGroup) && ($page_owner->forum_enable == "no")){
		// unset if not enabled for this plugin
		elgg_unregister_widget_type("group_forum_topics");
	}
}

function widget_group_forum_topics_init(){
	elgg_register_widget_type("group_forum_topics", elgg_echo("widgets:group_forum_topics:title"), elgg_echo("widgets:group_forum_topics:description"), "groups");
	widget_manager_add_widget_title_link("group_forum_topics", "[BASEURL]groups/forum/[GUID]/");
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_forum_topics_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_forum_topics_pagesetup");