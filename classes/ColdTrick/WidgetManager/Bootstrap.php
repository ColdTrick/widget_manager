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
}
