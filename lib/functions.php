<?php

define('ACCESS_LOGGED_OUT', -5);

/**
 * All functions related to widget manager
 */

/**
 * Returns a given array of widgets with the guids as key
 *
 * @param array &$widgets array of widgets to be sorted
 *
 * @return void
 */
function widget_manager_sort_widgets_guid(&$widgets): void {
	if (empty($widgets)) {
		return;
	}
	
	$new_widgets = [];
	
	foreach ($widgets as $row) {
		$new_widgets[$row->guid] = $row;
	}
	
	$widgets = $new_widgets;
}
	
/**
 * Updates the fixed widgets for a given context and user
 *
 * @param string $context   context of the widgets
 * @param int    $user_guid owner of the new widgets
 *
 * @return void
 */
function widget_manager_update_fixed_widgets(string $context, int $user_guid): void {
	elgg_call(ELGG_IGNORE_ACCESS, function() use ($context, $user_guid) {
		elgg_push_context('create_default_widgets');
	
		$options = [
			'type' => 'object',
			'subtype' => 'widget',
			'owner_guid' => elgg_get_site_entity()->guid,
			'private_setting_name_value_pairs' => [
				'context' => $context,
				'fixed' => 1.
				],
			'limit' => false,
		];
		
		// see if there are configured fixed widgets
		$configured_fixed_widgets = elgg_get_entities($options);
		widget_manager_sort_widgets_guid($configured_fixed_widgets);
		
		// fetch all currently configured widgets fixed AND not fixed
		$options['private_setting_name_value_pairs'] = ['context' => $context];
		$options['owner_guid'] = $user_guid;
		
		$user_widgets = elgg_get_entities($options);
		widget_manager_sort_widgets_guid($user_widgets);
		
		$default_widget_guids = [];
		
		// update current widgets
		if ($user_widgets) {
			foreach ($user_widgets as $guid => $widget) {
				$widget_fixed = $widget->fixed;
				$default_widget_guid = $widget->fixed_parent_guid;
				$default_widget_guids[] = $default_widget_guid;
				
				if (empty($default_widget_guid)) {
					continue;
				}
				
				if ($widget_fixed && !array_key_exists($default_widget_guid, $configured_fixed_widgets)) {
					// remove fixed status
					$widget->fixed = false;
				} elseif (!$widget_fixed && array_key_exists($default_widget_guid, $configured_fixed_widgets)) {
					// add fixed status
					$widget->fixed = true;
				}
				
				// need to recheck the fixed status as it could have been changed
				if ($widget->fixed && array_key_exists($default_widget_guid, $configured_fixed_widgets)) {
					// update settings for currently configured widgets
					
					// pull in settings
					$settings = $configured_fixed_widgets[$default_widget_guid]->getAllPrivateSettings();
					foreach ($settings as $name => $value) {
						$widget->$name = $value;
					}
					
					// access is no setting, but could also be controlled from the default widget
					$widget->access = $configured_fixed_widgets[$default_widget_guid]->access;
					
					// save the widget (needed for access update)
					$widget->save();
				}
			}
		}
		
		// add new fixed widgets
		if ($configured_fixed_widgets) {
			foreach ($configured_fixed_widgets as $guid => $widget) {
				if (in_array($guid, $default_widget_guids)) {
					continue;
				}
				
				// if no widget is found which is already linked to this default widget, clone the widget to the user
				$new_widget = clone $widget;
				$new_widget->container_guid = $user_guid;
				$new_widget->owner_guid = $user_guid;
				
				// pull in settings
				$settings = $widget->getAllPrivateSettings();
				
				foreach ($settings as $name => $value) {
					$new_widget->$name = $value;
				}
				
				$new_widget->save();
			}
		}
		
		// fixing order on all columns for this context, fixed widgets should always stay on top of other 'free' widgets
		foreach ([1,2,3] as $column) {
			// reuse previous declared options with a minor adjustment
			$options['private_setting_name_value_pairs'] = [
				'context' => $context,
				'column' => $column,
			];
			
			$column_widgets = elgg_get_entities($options);
			
			$free_widgets = [];
			$max_fixed_order = 0;
			
			if ($column_widgets) {
				foreach ($column_widgets as $widget) {
					if ($widget->fixed) {
						if ($widget->order > $max_fixed_order) {
							$max_fixed_order = $widget->order;
						}
					} else {
						$free_widgets[] = $widget;
					}
				}
				if (!empty($max_fixed_order) && !empty($free_widgets)) {
					foreach ($free_widgets as $widget) {
						$widget->order += $max_fixed_order;
					}
				}
			}
		}

		elgg_pop_context();
	});
	
	// set the user timestamp
	/** @var \ElggUser $user */
	$user = get_entity($user_guid);
	$user->setPluginSetting('widget_manager', $context . '_fixed_ts', time());
}
