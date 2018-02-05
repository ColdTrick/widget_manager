<?php

namespace ColdTrick\WidgetManager;

class Layouts {
	
	/**
	 * Updates fixed widgets on profile and dashboard
	 *
	 * @param \Elgg\Hook $hook hook
	 *
	 * @return void
	 */
	public static function checkFixedWidgets(\Elgg\Hook $hook) {
		if (elgg_in_context('default_widgets')) {
			return;
		}
		
		$context = elgg_get_context();
		if (!in_array($context, ['profile', 'dashboard'])) {
			// only check things if you are viewing a profile or dashboard page
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggUser) {
			return;
		}
		
		$fixed_ts = elgg_get_plugin_setting($context . '_fixed_ts', 'widget_manager');
		if (empty($fixed_ts)) {
			// there should always be a fixed ts, so fix it now. This situation only occurs after activating widget_manager the first time.
			$fixed_ts = time();
			elgg_set_plugin_setting($context . '_fixed_ts', $fixed_ts, 'widget_manager');
		}
		
		// get the ts of the profile/dashboard you are viewing
		$user_fixed_ts = elgg_get_plugin_user_setting($context . '_fixed_ts', $page_owner->guid, 'widget_manager');
		if ($user_fixed_ts < $fixed_ts) {
			widget_manager_update_fixed_widgets($context, $page_owner->guid);
		}
	}
}