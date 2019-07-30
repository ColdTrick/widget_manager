<?php

/**
 * Hooks for widget manager
 */
	
/**
 * Creates the ability to see content only for logged_out users
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return array
 */
function widget_manager_read_access_hook($hook_name, $entity_type, $return_value, $params) {
	
	if (elgg_is_logged_in() && !elgg_is_admin_logged_in()) {
		return;
	}
	
	if (empty($return_value)) {
		$return_value = [];
	} else {
		if (!is_array($return_value)) {
			$return_value = [$return_value];
		}
	}
	
	$return_value[] = ACCESS_LOGGED_OUT;
	
	return $return_value;
}

/**
 * Flattens the settings value for index managers
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return void
 */
function widget_manager_index_manager_setting_plugin_hook_handler($hook_name, $entity_type, $return_value, $params) {
	if (elgg_extract('plugin_id', $params) !== 'widget_manager') {
		return;
	}
	
	if (elgg_extract('name', $params) !== 'index_managers') {
		return;
	}
	
	return implode(',', $return_value);
}
	
/**
 * Registers the extra context permissions check hook
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return void
 */
function widget_manager_widgets_action_hook_handler($hook_name, $entity_type, $return_value, $params) {
	if ($entity_type == 'widgets/move') {
		$widget_guid = (int) get_input('widget_guid');
		if (empty($widget_guid)) {
			return;
		}

		$widget = get_entity($widget_guid);
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		$widget_context = $widget->context;
		
		$index_widgets = elgg_get_widget_types('index');
		
		foreach ($index_widgets as $handler => $index_widget) {
			$contexts = $index_widget->context;
			$contexts[] = $widget_context;
			elgg_register_widget_type($handler, $index_widget->name, $index_widget->description, $contexts, $index_widget->multiple);
		}
	} elseif ($entity_type == 'widgets/add') {
		elgg_register_plugin_hook_handler('permissions_check', 'site', '\ColdTrick\WidgetManager\Access::writeAccessForIndexManagers');
	}
}

/**
 * Checks if current user can edit a widget if it is in a context he/she can manage
 *
 * @param string $hook_name    name of the hook
 * @param string $entity_type  type of the hook
 * @param string $return_value current return value
 * @param array  $params       hook parameters
 *
 * @return boolean
 */
function widget_manager_permissions_check_object_hook_handler($hook_name, $entity_type, $return_value, $params) {
	$user = elgg_extract('user', $params);
	$entity = elgg_extract('entity', $params);
	
	if ($return_value || !($user instanceof \ElggUser)|| !($entity instanceof \ElggWidget)) {
		return;
	}
	
	if (!$entity->getOwnerEntity() instanceof \ElggSite) {
		// special permission is only for widget owned by site
		return;
	}
	
	$context = $entity->context;
	if ($context) {
		return elgg_can_edit_widget_layout($context, $user->getGUID());
	}
}
