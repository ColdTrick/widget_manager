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
	 * @return null|array
	 */
	public static function setWriteAccess(\Elgg\Event $event): ?array {
		
		$input_params = $event->getParam('input_params', []);
		if (elgg_extract('entity_type', $input_params) !== 'object' || elgg_extract('entity_subtype', $input_params) !== 'widget') {
			return null;
		}
		
		$container = get_entity(elgg_extract('container_guid', $input_params));
		if (!$container instanceof \ElggEntity) {
			return null;
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
				return null;
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
		
		return null;
	}
	
	/**
	 * Allow write access for index managers
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'site'
	 *
	 * @return null|bool
	 */
	public static function writeAccessForIndexManagers(\Elgg\Event $event): ?bool {
		$result = $event->getValue();
		if ($result) {
			return $result;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggSite) {
			return null;
		}
		
		$user = $event->getUserParam();
		if (!$user instanceof \ElggUser) {
			return null;
		}
		
		$index_managers = explode(',', elgg_get_plugin_setting('index_managers', 'widget_manager', ''));
		return in_array($user->guid, $index_managers) ?: null;
	}
	
	/**
	 * Creates the ability to see content only for logged_out users
	 *
	 * @param \Elgg\Event $event 'access:collections:read', 'user'
	 *
	 * @return null|array
	 */
	public static function addLoggedOutReadAccess(\Elgg\Event $event): ?array {
		
		if (elgg_is_logged_in() && !elgg_is_admin_logged_in()) {
			return null;
		}
		
		$return_value = $event->getValue() ?: [];
		
		if (!is_array($return_value)) {
			$return_value = [$return_value];
		}
		
		$return_value[] = ACCESS_LOGGED_OUT;
		
		return $return_value;
	}
	
	/**
	 * Checks if current user can edit a widget if it is in a context he/she can manage
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'object'
	 *
	 * @return null|bool
	 */
	public static function canEditWidgetOnManagedLayout(\Elgg\Event $event): ?bool {
		$user = $event->getUserParam();
		$entity = $event->getEntityParam();
		
		if ($event->getValue() || !$user instanceof \ElggUser || !$entity instanceof \ElggWidget) {
			return null;
		}
		
		if (!$entity->getOwnerEntity() instanceof \ElggSite) {
			// special permission is only for widget owned by site
			return null;
		}
		
		$context = $entity->context;
		return $context ? elgg_can_edit_widget_layout($context, $user->guid) : null;
	}
	
	/**
	 * Registers the extra context permissions check event
	 *
	 * @param \Elgg\Event $event 'action:validate', 'widgets/[add|delete|move|save]'
	 *
	 * @return void
	 */
	public static function moreRightsForWidgetManager(\Elgg\Event $event): void {
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
			self::registerSQLBypass($widget_guid);
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

	/**
	 * Registers a bypass sql suffix
	 *
	 * @param int $guid GUID of the entity to register sql bypass for
	 *
	 * @return void
	 */
	protected static function registerSQLBypass(int $guid): void {
		elgg_register_event_handler('get_sql', 'access', function(\Elgg\Event $event) use ($guid) {
			if ($event->getParam('ignore_access')) {
				// no need to give extra access
				return null;
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

			$result['ors']['special_widget_access'] = $qb->compare($alias($guid_column), '=', $guid);

			return $result;
		});
	}
	
	/**
	 * Only allow widget page delete by admins
	 *
	 * @param \Elgg\Event $event 'permissions_check:delete', 'object'
	 *
	 * @return boolean
	 */
	public static function onlyAdminsCanDeleteWidgetPages(\Elgg\Event $event) {
		$user = $event->getUserParam();
		$entity = $event->getEntityParam();
		
		if (!$user instanceof \ElggUser || !$entity instanceof \WidgetPage) {
			return;
		}
		
		return $user->isAdmin();
	}

	/**
	 * Only allow widget edit for private widgets
	 *
	 * @param \Elgg\Event $event 'view_vars', 'object/widget/edit'
	 *
	 * @return null|array
	 */
	public static function allowPrivateWidgetEdit(\Elgg\Event $event): ?array {
		$result = $event->getValue();
		if (elgg_extract('entity', $result) instanceof \ElggEntity) {
			return $result;
		}

		$guid = (int) elgg_extract('guid', $result);
		$entity = elgg_call(ELGG_IGNORE_ACCESS, function() use ($guid) {
			return get_entity($guid);
		});

		if ($entity->canEdit()) {
			$result['entity'] = $entity;
			self::registerSQLBypass($guid);
		}

		return $result;
	}
}
