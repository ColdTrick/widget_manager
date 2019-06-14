<?php

namespace ColdTrick\WidgetManager;

/**
 * Access
 */
class Access {
	
	/**
	 * Sets the write access array for widgets
	 *
	 * @param \Elgg\Hook $hook Hook
	 *
	 * @return []
	 */
	public static function setWriteAccess(\Elgg\Hook $hook) {
		
		$input_params = $hook->getParam('input_params', []);
		
		$widget = elgg_extract('entity', $input_params);
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		$result = $hook->getValue();
		
		$widget_context = $widget->context;
		
		if ($widget_context === 'groups') {
			$group = $widget->getContainerEntity();
			if ($group instanceof \ElggGroup) {
				$acl = $group->getOwnedAccessCollection('group_acl');
				if ($acl) {
					return [
						$acl->id => elgg_echo('groups:access:group'),
						ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
						ACCESS_PUBLIC => elgg_echo('access:label:public')
					];
				}
			}
		} elseif ($widget->container_guid === elgg_get_site_entity()->guid) {
			// admins only have the following options for index widgets
			if (elgg_is_admin_logged_in()) {
				return [
					ACCESS_PRIVATE => elgg_echo('access:admin_only'),
					ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
					ACCESS_LOGGED_OUT => elgg_echo('access:label:logged_out'),
					ACCESS_PUBLIC => elgg_echo('access:label:public')
				];
			} elseif(elgg_can_edit_widget_layout($widget_context)) {
				// for non admins that can manage this widget context
				return [
					ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
					ACCESS_PUBLIC => elgg_echo('access:label:public')
				];
			}
		}
	}
}