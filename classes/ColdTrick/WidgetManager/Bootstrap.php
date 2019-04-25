<?php

namespace ColdTrick\WidgetManager;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		// register plugin hooks
	// 	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'widget_manager_write_access_hook', 999);
		elgg_register_plugin_hook_handler('access:collections:read', 'user', 'widget_manager_read_access_hook');
		elgg_register_plugin_hook_handler('register', 'menu:widget', '\ColdTrick\WidgetManager\Menus::addFixDefaultWidgetMenuItem');
		elgg_register_plugin_hook_handler('prepare', 'menu:widget', '\ColdTrick\WidgetManager\Menus::prepareWidgetEditDeleteMenuItems');
		elgg_register_plugin_hook_handler('permissions_check', 'widget_layout', '\ColdTrick\WidgetManager\Widgets::layoutPermissionsCheck');
		elgg_register_plugin_hook_handler('handlers', 'widgets', '\ColdTrick\WidgetManager\Widgets::applyWidgetsConfig', 9999);
		elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\WidgetManager\Widgets::getWidgetURL');
		elgg_register_plugin_hook_handler('action:validate', 'widgets/add', 'widget_manager_widgets_action_hook_handler');
		elgg_register_plugin_hook_handler('action:validate', 'widgets/move', 'widget_manager_widgets_action_hook_handler');
		elgg_register_plugin_hook_handler('permissions_check', 'object', 'widget_manager_permissions_check_object_hook_handler');
		elgg_register_plugin_hook_handler('setting', 'plugin', 'widget_manager_index_manager_setting_plugin_hook_handler');
		elgg_register_plugin_hook_handler('view_vars', 'groups/profile/widgets', '\ColdTrick\WidgetManager\Groups::getGroupWidgetsLayout');
		elgg_register_plugin_hook_handler('view_vars', 'page/layouts/widgets', '\ColdTrick\WidgetManager\Widgets::checkFixedWidgets');
		elgg_register_plugin_hook_handler('view_vars', 'object/widget/body', '\ColdTrick\WidgetManager\Widgets::getContentFromCache');
		elgg_register_plugin_hook_handler('view_vars', 'object/widget/elements/controls', '\ColdTrick\WidgetManager\Widgets::preventControls');
		elgg_register_plugin_hook_handler('view', 'object/widget/body', '\ColdTrick\WidgetManager\Widgets::saveContentInCache', 9999);
		elgg_register_plugin_hook_handler('widget_settings', 'all', '\ColdTrick\WidgetManager\Widgets::clearWidgetCacheOnSettingsSave');
		elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\WidgetManager\Menus::registerAdminPageMenu');
		elgg_register_plugin_hook_handler('register', 'menu:entity', '\ColdTrick\WidgetManager\Menus::addWidgetPageEntityMenuItems');
		elgg_register_plugin_hook_handler('register', 'menu:widget_toggle', '\ColdTrick\WidgetManager\Menus::addWidgetToggleControls');
	
		
		elgg_register_event_handler('create', 'object', '\ColdTrick\WidgetManager\Widgets::fixPrivateAccess');
		elgg_register_event_handler('all', 'object', '\ColdTrick\WidgetManager\Widgets::createFixedParentMetadata', 1000); // is only a fallback
		
		
		// extend CSS
		elgg_extend_view('elements/widgets.css', 'css/widget_manager/site.css');
	// 	elgg_extend_view('css/elgg', 'css/widget_manager/global.css');
		elgg_extend_view('css/admin', 'css/widget_manager/admin.css');
	// 	elgg_extend_view('css/admin', 'css/widget_manager/global.css');
		elgg_extend_view('js/elgg', 'js/widget_manager/site.js');
		elgg_extend_view('object/widget/elements/content', 'widget_manager/widgets/custom_more');
		elgg_extend_view('object/widget/header', 'object/widget/toggle', 400);
			
	
		elgg_register_ajax_view('forms/widget_manager/widget_page');
		elgg_register_ajax_view('widget_manager/widgets/settings');
		elgg_register_ajax_view('widgets/user_search/content');
		
		$this->registerIndexRoute();
		$this->registerWidgetPagesRoutes();
		$this->initGroups();
	}

	/**
	 * Conditionally registers a route for the index page
	 *
	 * @return void
	 */
	protected function registerIndexRoute() {
		// @todo check -> need later priority to win over walledgarden
		$setting = elgg_get_plugin_setting('custom_index', 'widget_manager');
		if (empty($setting)) {
			return;
		}
		
		list($non_loggedin, $loggedin) = explode('|', $setting);
		
		if ((!elgg_is_logged_in() && !empty($non_loggedin)) || (elgg_is_logged_in() && !empty($loggedin)) || (elgg_is_admin_logged_in() && (get_input('override') == true))) {
			elgg_register_route('index', [
				'path' => '/',
				'resource' => 'widget_manager/custom_index',
			]);
		}
	}
	
	/**
	 * Registers routes for the extra contexts pages
	 *
	 * @return void
	 */
	protected function registerWidgetPagesRoutes() {
		
		$urls = $this->getWidgetPagesUrls();
		
		foreach ($urls as $url) {
			elgg_register_route($url, [
				'path' => $url,
				'resource' => 'widget_manager/widget_page',
			]);
		}
	}
	
	protected function getWidgetPagesUrls() {
		
		$urls = elgg_load_system_cache('widget_pages');
		if ($urls) {
			return $urls;
		}
		
		$urls = [];
		
		$metadata = elgg_get_metadata([
			'type' => 'object',
			'subtype' => \WidgetPage::SUBTYPE,
			'limit' => false,
			'batch' => true,
			'metadata_name' => 'url',
		]);
		
		foreach ($metadata as $md) {
			$urls[] = $md->value;
		}
		
		elgg_save_system_cache('widget_pages', $urls);
		
		return $urls;
	}
	
	/**
	 * Used to perform initialization of the group widgets features.
	 *
	 * @return void
	 */
	protected function initGroups() {
		if (!elgg_is_active_plugin('groups')) {
			return;
		}
		
		$group_enable = elgg_get_plugin_setting('group_enable', 'widget_manager');
		if (!in_array($group_enable, ['yes', 'forced'])) {
			return;
		}
	
		elgg_extend_view('groups/edit', 'widget_manager/forms/groups_widget_access');
			
		// cleanup widgets in group context
		elgg_extend_view('page/layouts/widgets/add_panel', 'widget_manager/group_tool_widgets', 400);
			
		if ($group_enable == 'yes') {
			// add the widget manager tool option
			$group_option_enabled = (elgg_get_plugin_setting('group_option_default_enabled', 'widget_manager') == 'yes');
	
			if (elgg_get_plugin_setting('group_option_admin_only', 'widget_manager') !== 'yes') {
				// add the tool option for group admins
				$this->elgg()->group_tools->register('widget_manager', [
					'label' => elgg_echo('widget_manager:groups:enable_widget_manager'),
					'default_on' => $group_option_enabled,
				]);
			} elseif (elgg_is_admin_logged_in()) {
				$this->elgg()->group_tools->register('widget_manager', [
					'label' => elgg_echo('widget_manager:groups:enable_widget_manager'),
					'default_on' => $group_option_enabled,
				]);
			}
		}
			
		// register event to make sure all groups have the group option enabled if forces
		// and configure tool enabled widgets
		elgg_register_event_handler('update', 'group', '\ColdTrick\WidgetManager\Groups::updateGroupWidgets');
		elgg_register_event_handler('create', 'object', '\ColdTrick\WidgetManager\Groups::addGroupWidget');
		elgg_register_event_handler('delete', 'object', '\ColdTrick\WidgetManager\Groups::deleteGroupWidget');
			
		// make default widget management available
		elgg_register_plugin_hook_handler('get_list', 'default_widgets', '\ColdTrick\WidgetManager\DefaultWidgets::addGroupsContextToDefaultWidgets');
	}
}
