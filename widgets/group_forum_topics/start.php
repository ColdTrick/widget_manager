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
	elgg_register_widget_type("group_forum_topics", elgg_echo("discussion:group"), elgg_echo("widgets:group_forum_topics:description"), "groups");
	elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "widget_group_forum_topics_url");
}

function widget_group_forum_topics_url($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];
	if(empty($result) && ($widget instanceof ElggWidget) && $widget->handler == "group_forum_topics"){
		if(($page_owner = elgg_get_page_owner_entity()) && ($page_owner instanceof ElggGroup)){
			$result = "/discussion/owner/" . $page_owner->getGUID();
		}
	}
	return $result;
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_forum_topics_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_forum_topics_pagesetup");