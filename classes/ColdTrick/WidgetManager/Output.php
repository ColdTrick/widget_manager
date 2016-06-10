<?php

namespace ColdTrick\WidgetManager;

class Output {
	
	/**
	 * Returns a rss widget specific date_time notation
	 *
	 * @param string $hook_name    name of the hook
	 * @param string $entity_type  type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       hook parameters
	 *
	 * @return string
	 */
	public static function rssFriendlyTime($hook_name, $entity_type, $return_value, $params) {
		if (empty($params['time'])) {
			return;
		}
	
		if (!elgg_in_context('rss_date')) {
			return;
		}
	
		$date_info = getdate($params['time']);
	
		$date_array = [
			elgg_echo('date:weekday:' . $date_info['wday']),
			elgg_echo('date:month:' . str_pad($date_info['mon'], 2, '0', STR_PAD_LEFT), [$date_info['mday']]),
			$date_info['year'],
		];
	
		return implode(' ', $date_array);
	}
}