<?php

namespace ColdTrick\WidgetManager;

class Widgets {
	
	/**
	 * Updates widget access for private widgets in group or site
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function fixPrivateAccess($event, $object_type, $object) {
	
		if (!elgg_instanceof($object, 'object', 'widget', 'ElggWidget')) {
			return;
		}

		if ((int) $object->access_id !== ACCESS_PRIVATE) {
			return;	
		}

		$owner = $object->getOwnerEntity();
		
		// Updates access for privately created widgets in a group or on site
		$old_ia = elgg_set_ignore_access();
		if ($owner instanceof ElggGroup) {
			$object->access_id = $owner->group_acl;
			$object->save();
		} elseif ($owner instanceof ElggSite) {
			$object->access_id = ACCESS_PUBLIC;
			$object->save();
		}
		elgg_set_ignore_access($old_ia);
	}

	/**
	 * Links a widget to a multidashboard
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function linkWidgetToMultiDashboard($event, $object_type, $object) {
	
		if (!elgg_instanceof($object, 'object', 'widget', 'ElggWidget')) {
			return;
		}

		$dashboard_guid = get_input('multi_dashboard_guid');
		if (empty($dashboard_guid) || !widget_manager_multi_dashboard_enabled()) {
			return;
		}
	
		$dashboard = get_entity($dashboard_guid);
		if (!elgg_instanceof($dashboard, 'object', MultiDashboard::SUBTYPE, 'MultiDashboard')) {
			return;
		}
	
		// Adds a relation between a widget and a multidashboard object
		add_entity_relationship($object->getGUID(), MultiDashboard::WIDGET_RELATIONSHIP, $dashboard->getGUID());
	}
	
	/**
	 * Sets the fixed parent guid to default widgets to be used when cloning, so relationship can stay intact.
	 *
	 * @param string $event       name of the system event
	 * @param string $object_type type of the event
	 * @param mixed  $object      object related to the event
	 *
	 * @return void
	 */
	public static function createFixedParentMetadata($event, $object_type, $object) {
		if (!($object instanceof ElggWidget) || !in_array($event, ['create', 'update', 'delete'])) {
			return;
		}
	
		if (!stristr($_SERVER['HTTP_REFERER'], '/admin/appearance/default_widgets')) {
			return;
		}
	
		// on create set a parent guid
		if ($event == 'create') {
			$object->fixed_parent_guid = $object->guid;
		}
	
		// update time stamp
		$context = $object->context;
		if (empty($context)) {
			// only situation is on create probably, as context is metadata and saved after creation of the object, this is the fallback
			$context = get_input('context', false);
		}
	
		if ($context) {
			elgg_set_plugin_setting($context . '_fixed_ts', time(), 'widget_manager');
		}
	}
}