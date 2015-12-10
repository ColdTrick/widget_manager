<?php

$link = get_input("link");
$title = get_input("title");

$favorite = widget_manager_widgets_favorites_is_linked($link);

if ((!empty($link) && !empty($title)) || !empty($favorite)) {

	if (elgg_instanceof($favorite, "object", "widget_favorite")) {
		// if exists delete
		if ($favorite->delete()) {
			system_message(elgg_echo("widgets:favorites:delete:success"));
		} else {
			register_error(elgg_echo("widgets:favorites:delete:error"));
		}
	} elseif (!empty($link) && !empty($title)) {
		if (!empty($favorite)) {
			// silent return, probably double clicked the action
			forward(REFERER);
		}
		
		// create new favorite
		$object = new ElggObject();
		$object->title = $title;
		$object->description = $link;
		$object->subtype = "widget_favorite";
		$object->access_id = ACCESS_PRIVATE;
		
		if ($object->save()) {
			system_message(elgg_echo("widgets:favorites:save:success"));
			
			$text = elgg_view_icon("star-alt");
			$href = "action/favorite/toggle?guid=" . $object->getGUID();
			$title = elgg_echo("widgets:favorites:menu:remove");
			
			echo elgg_view("output/url", array("text" => $text, "href" => $href, "title" => $title, "is_action" => true));
		
		} else {
			register_error(elgg_echo("widgets:favorites:save:error"));
		}
	} else {
		register_error(elgg_echo("widgets:favorites:toggle:missing_input"));
	}
} else {
	register_error(elgg_echo("widgets:favorites:toggle:missing_input"));
}
	
forward(REFERER);
