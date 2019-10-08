<?php

namespace ColdTrick\WidgetManager;

/**
 * Access
 */
class Access {
	
	/**
	 * Sets the write access array for widgets
	 *
	 * @param \Elgg\Hook $hook 'access:collections:write', 'all'
	 *
	 * @return []
	 */
	public static function setWriteAccess(\Elgg\Hook $hook) {
		
		$input_params = $hook->getParam('input_params', []);
		if (elgg_extract('entity_type', $input_params) !== 'object' || elgg_extract('entity_subtype', $input_params) !== 'widget') {
			return;
		}
		
		$container = get_entity(elgg_extract('container_guid', $input_params));
		if (!$container instanceof \ElggEntity) {
			return;
		}
		
		if ($container instanceof \ElggGroup) {
			$acl = $container->getOwnedAccessCollection('group_acl');
			if ($acl) {
				return [
					$acl->id => elgg_echo('groups:access:group'),
					ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
					ACCESS_PUBLIC => elgg_echo('access:label:public')
				];
			}
		} elseif ($container instanceof \ElggSite) {
			// sepcial options for index widgets
			
			$widget = elgg_extract('entity', $input_params);
			if (!$widget instanceof \ElggWidget) {
				return;
			}
			
			if (elgg_can_edit_widget_layout($widget->context)) {
				return [
					ACCESS_PRIVATE => elgg_echo('access:admin_only'),
					ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
					ACCESS_LOGGED_OUT => elgg_echo('access:label:logged_out'),
					ACCESS_PUBLIC => elgg_echo('access:label:public')
				];
			}
		}
	}
	
	/**
	 * Allow write access for index managers
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'site'
	 *
	 * @return []
	 */
	public static function writeAccessForIndexManagers(\Elgg\Hook $hook) {
		$result = $hook->getValue();
		
		if ($result) {
			return;
		}
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggSite) {
			return;
		}
		
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$index_managers = explode(',', elgg_get_plugin_setting('index_managers', 'widget_manager', ''));
		if (in_array($user->guid, $index_managers)) {
			return true;
		}
	}
	
	/**
	 * Creates the ability to see content only for logged_out users
	 *
	 * @param \Elgg\Hook $hook 'access:collections:read', 'user'
	 *
	 * @return array
	 */
	public static function addLoggedOutReadAccess(\Elgg\Hook $hook) {
		
		if (elgg_is_logged_in() && !elgg_is_admin_logged_in()) {
			return;
		}
		$return_value = $hook->getValue();
		
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
	 * Checks if current user can edit a widget if it is in a context he/she can manage
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'object'
	 *
	 * @return boolean
	 */
	public static function canEditWidgetOnManagedLayout(\Elgg\Hook $hook) {
		$user = $hook->getUserParam();
		$entity = $hook->getEntityParam();
		
		if ($hook->getValue() || !($user instanceof \ElggUser)|| !($entity instanceof \ElggWidget)) {
			return;
		}
		
		if (!$entity->getOwnerEntity() instanceof \ElggSite) {
			// special permission is only for widget owned by site
			return;
		}
		
		$context = $entity->context;
		if ($context) {
			return elgg_can_edit_widget_layout($context, $user->guid);
		}
	}
	
	/**
	 * Registers the extra context permissions check hook
	 *
	 * @param \Elgg\Hook $hook_name 'action:validate', 'widgets/[add|move]'
	 *
	 * @return void
	 */
	public static function moreRightsForWidgetManager(\Elgg\Hook $hook) {
		if ($hook->getType() == 'widgets/move') {
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
		} elseif ($hook->getType() == 'widgets/add') {
			elgg_register_plugin_hook_handler('permissions_check', 'site', '\ColdTrick\WidgetManager\Access::writeAccessForIndexManagers');
		}
	}
}