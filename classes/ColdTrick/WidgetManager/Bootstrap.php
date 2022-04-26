<?php

namespace ColdTrick\WidgetManager;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
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
					
		// register event to make sure all groups have the group option enabled if forces
		// and configure tool enabled widgets
		elgg_register_event_handler('update', 'group', '\ColdTrick\WidgetManager\Groups::updateGroupWidgets');
		elgg_register_event_handler('create', 'object', '\ColdTrick\WidgetManager\Groups::addGroupWidget');
		elgg_register_event_handler('delete', 'object', '\ColdTrick\WidgetManager\Groups::deleteGroupWidget');
			
		// make default widget management available
		elgg_register_plugin_hook_handler('get_list', 'default_widgets', '\ColdTrick\WidgetManager\DefaultWidgets::addGroupsContextToDefaultWidgets');
	}
}
