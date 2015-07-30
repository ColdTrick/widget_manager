<?php
/**
 * Update widgets access for group
 */

$group_guid = (int) get_input("group_guid");
$new_access = get_input("widget_access_level"); // can't cast directly to int because of ACCESS_PRIVATE

$forward_url = REFERER;

if (!empty($group_guid) && ($new_access !== null)) {
	$group = get_entity($group_guid);
	
	if (!empty($group) && elgg_instanceof($group, "group")) {
		if ($group->canEdit()) {
			$new_access = (int) $new_access;
		
			$options = [
				'type' => 'object',
				'subtype' => 'widget',
				'owner_guid' => $group->getGUID(),
				'private_setting_name' => 'context',
				'private_setting_value' => 'groups',
				'limit' => false
			];
			
			$widgets = elgg_get_entities_from_private_settings($options);
			if ($widgets) {
				foreach ($widgets as $widget) {
					$widget->access_id = $new_access;
					$widget->save();
				}
			}
			
			system_message(elgg_echo("widget_manager:action:groups:update_widget_access:success"));
			$forward_url = $group->getURL();
		} else {
			register_error(elgg_echo("groups:cantedit"));
		}
	} else {
		register_error(elgg_echo("groups:notfound:details"));
	}
} else {
	register_error(elgg_echo("InvalidParameterException:MissingParameter"));
}

forward($forward_url);