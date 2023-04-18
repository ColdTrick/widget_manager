<?php

namespace ColdTrick\WidgetManager\Upgrades;

use ColdTrick\WidgetManager;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate extra contexts plugin settings to WidgetPage
 */
class CreateWidgetPages extends AsynchronousUpgrade {
	
	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2018052400;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		
		$plugin = elgg_get_plugin_from_id('widget_manager');
		$contexts = elgg_string_to_array((string) $plugin->extra_contexts);
				
		return count($contexts);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		
		$plugin = elgg_get_plugin_from_id('widget_manager');
		$contexts = elgg_string_to_array((string) $plugin->extra_contexts);
		
		$contexts_config = json_decode($plugin->extra_contexts_config, true);
		if (!is_array($contexts_config)) {
			$contexts_config = [];
		}
		
		foreach ($contexts as $context) {
			$context_config = elgg_extract($context, $contexts_config, []);
			
			$widget_page = new \WidgetPage();
			$widget_page->url = $context;
			$widget_page->layout = elgg_extract('layout', $context_config, '33|33|33');
			
			$widget_page->save();
			
			$managers = elgg_string_to_array((string) elgg_extract('manager', $context_config, ''));
			foreach ($managers as $username) {
				$user = elgg_get_user_by_username($username);
				if ($user) {
					$widget_page->addManager($user);
				}
			}
			
			$this->migrateWidgets($context, $widget_page);
		}
				
		$plugin->unsetSetting('extra_contexts');
		$plugin->unsetSetting('extra_contexts_config');
				
		$result->addSuccesses(count($contexts));
	}
	
	protected function migrateWidgets($old_context, \WidgetPage $new_page) {
		$widgets = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'widget',
			'metadata_name' => 'context',
			'metadata_value' => $old_context,
			'batch' => true,
			'batch_inc_offset' => false,
			'limit' => false,
		]);
		
		foreach ($widgets as $widget) {
			$widget->owner_guid = $new_page->guid;
			$widget->container_guid = $new_page->guid;
			
			$widget->context = 'index';
			$widget->save();
		}
	}
}
