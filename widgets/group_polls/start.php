<?php 
/* init file for group_polls widget */

function widget_group_polls_pagesetup(){
    $page_owner = elgg_get_page_owner_entity();

    elgg_load_library('elgg:polls');

    if (($page_owner instanceof ElggGroup) && ! polls_activated_for_group($page_owner)) {
        elgg_unregister_widget_type("group_polls");
    } else {
        elgg_load_js('elgg.polls');
    }
}

function widget_group_polls_init(){
    if (elgg_is_active_plugin("polls")) {
        elgg_register_widget_type(
            "group_polls",
            elgg_echo("polls:group_polls"),
            elgg_echo("widgets:group_polls:description"),
            "groups",
            false);
        widget_manager_add_widget_title_link("group_polls", "[BASEURL]polls/group/[GUID]/all");
    }
}

elgg_register_event_handler("widgets_init", "widget_manager", "widget_group_polls_init");
elgg_register_event_handler("widgets_pagesetup", "widget_manager", "widget_group_polls_pagesetup");