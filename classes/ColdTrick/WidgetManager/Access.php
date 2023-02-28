<?php

namespace ColdTrick\WidgetManager;

use Elgg\Database\QueryBuilder;

/**
 * Access
 */
class Access {
	
	/**
	 * Sets the write access array for widgets
	 *
	 * @param \Elgg\Event $event 'access:collections:write', 'all'
	 *
	 * @return array
	 */
	public static function setWriteAccess(\Elgg\Event $event) {
		
		$input_params = $event->getParam('input_params', []);
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
					ACCESS_PUBLIC => elgg_echo('access:label:public'),
				];
			}
		} elseif ($container instanceof \ElggSite) {
			// special options for index widgets
			
			$widget = elgg_extract('entity', $input_params);
			if (!$widget instanceof \ElggWidget) {
				return;
			}
			
			if (elgg_can_edit_widget_layout($widget->context)) {
				return [
					ACCESS_PRIVATE => elgg_echo('access:admin_only'),
					ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
					ACCESS_LOGGED_OUT => elgg_echo('access:label:logged_out'),
					ACCESS_PUBLIC => elgg_echo('access:label:public'),
				];
			}
		}
	}
	
	/**
	 * Allow write access for index managers
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'site'
	 *
	 * @return array
	 */
	public static function writeAccessForIndexManagers(\Elgg\Event $event) {
		$result = $event->getValue();
		
		if ($result) {
			return;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggSite) {
			return;
		}
		
		$user = $event->getUserParam();
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
	 * @param \Elgg\Event $event 'access:collections:read', 'user'
	 *
	 * @return array
	 */
	public static function addLoggedOutReadAccess(\Elgg\Event $event) {
		
		if (elgg_is_logged_in() && !elgg_is_admin_logged_in()) {
			return;
		}
		
		$return_value = $event->getValue();
		
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
	 * @param \Elgg\Event $event 'permissions_check', 'object'
	 *
	 * @return boolean
	 */
	public static function canEditWidgetOnManagedLayout(\Elgg\Event $event) {
		$user = $event->getUserParam();
		$entity = $event->getEntityParam();
		
		if ($event->getValue() || !$user instanceof \ElggUser || !$entity instanceof \ElggWidget) {
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
	 * Registers the extra context permissions check event
	 *
	 * @param \Elgg\Event $event 'action:validate', 'widgets/[add|delete|move|save]'
	 *
	 * @return void
	 */
	public static function moreRightsForWidgetManager(\Elgg\Event $event) {
		if ($event->getType() === 'widgets/add') {
			elgg_register_event_handler('permissions_check', 'site', '\ColdTrick\WidgetManager\Access::writeAccessForIndexManagers');
			return;
		}
		
		$widget_guid = (int) get_input('widget_guid', get_input('guid'));
		if (empty($widget_guid)) {
			return;
		}
		
		$widget = elgg_call(ELGG_IGNORE_ACCESS, function() use ($widget_guid) {
			return get_entity($widget_guid);
		});
		
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		if ($widget->canEdit()) {
			// the widgets action might not be able to get privately owned index widgets
			elgg_register_event_handler('get_sql', 'access', function(\Elgg\Event $event) use ($widget_guid) {
				if ($event->getParam('ignore_access')) {
					// no need to give extra access
					return;
				}
				
				/**
				 * @var QueryBuilder $qb
				 */
				$qb = $event->getParam('query_builder');
				$table_alias = $event->getParam('table_alias');
				$guid_column = $event->getParam('guid_column');
				
				$alias = function ($column) use ($table_alias) {
					return $table_alias ? "{$table_alias}.{$column}" : $column;
				};
				
				$result = $event->getValue();
				
				$result['ors']['special_widget_access'] = $qb->compare($alias($guid_column), '=', $widget_guid);
				
				return $result;
			});
		}
		
		if ($event->getType() === 'widgets/move') {
			// allow 'index' widgets to be added to the same context as the current widget
			$widget_context = $widget->context;
			
			$index_widgets = elgg_get_widget_types('index');
			
			foreach ($index_widgets as $handler => $index_widget) {
				$contexts = $index_widget->context;
				$contexts[] = $widget_context;
				elgg_register_widget_type([
					'id' => $handler,
					'name' => $index_widget->name,
					'description' => $index_widget->description,
					'context' => $contexts,
					'multiple' => $index_widget->multiple,
				]);
			}
		}
	}
}
