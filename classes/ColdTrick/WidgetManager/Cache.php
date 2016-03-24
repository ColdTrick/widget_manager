<?php

namespace ColdTrick\WidgetManager;

class Cache {
	
	/**
	 * Listen to the cache flush event to invalidate cache of widgets content
	 *
	 * @param string $event  the name of the event
	 * @param string $type   the type of the event
	 * @param mixed  $object supplied param
	 *
	 * @return void
	 */
	public static function resetWidgetsCache($event, $type, $object) {
		
		$ia = elgg_set_ignore_access(true);
		$batch = new \ElggBatch('elgg_get_entities_from_private_settings', [
			'type' => 'object',
			'subtype' => 'widget',
			'limit' => false,
			'private_setting_name_value_pairs' => [
				'name' => 'widget_manager_cached_data',
				'value' => 0,
				'operand' => '>',
			],
		]);
		
		$batch->setIncrementOffset(false);
		
		foreach ($batch as $entity) {
			$entity->widget_manager_cached_data = 0;
		}
		
		elgg_set_ignore_access($ia);
	}
}