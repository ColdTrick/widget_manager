<?php

namespace ColdTrick\WidgetManager\Upgrades;

use Elgg\Upgrade\Result;

class MigrateWidgetSettings extends \Elgg\Upgrade\AsynchronousUpgrade {
	
	/**
	 * @inheritDoc
	 */
	public function getVersion(): int {
		return 2023051101;
	}
	
	/**
	 * @inheritDoc
	 */
	public function shouldBeSkipped(): bool {
		return empty($this->countItems());
	}
	
	/**
	 * @inheritDoc
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}
	
	/**
	 * @inheritDoc
	 */
	public function countItems(): int {
		global $jerome;
		$jerome = true;
		$r = elgg_get_metadata($this->getOptions(['count' => true]));
		$jerome = false;
		return $r;
	}
	
	/**
	 * @inheritDoc
	 */
	public function run(Result $result, $offset): Result {
		/* @var $batch \ElggBatch */
		$batch = elgg_get_metadata($this->getOptions([
			'offset' => $offset,
		]));
		/* @var $metadata \ElggMetadata */
		foreach ($batch as $metadata) {
			$json = @json_decode($metadata->value, true);
			if (!isset($json)) {
				// not json, leave intact
				$result->addFailures();
				continue;
			}
			
			$json = array_filter($json);
			if (empty($json)) {
				// nothing left after clean, so remove
				if ($metadata->delete()) {
					$result->addSuccesses();
				} else {
					$result->addFailures();
				}
				continue;
			}
			
			$json = array_values($json);
			if (count($json) === 1) {
				// only one value, so save as the new value
				$metadata->value = $json[0];
				if ($metadata->save()) {
					$result->addSuccesses();
				} else {
					$result->addFailures();
				}
				continue;
			}
			
			// multiple value, so save the array with the original entity
			/* @var $widget \ElggWidget */
			$widget = $metadata->getEntity();
			$metadata_name = $metadata->name;
			$widget->{$metadata_name} = $json;
			
			$result->addSuccesses();
		}
		
		return $result;
	}
	
	/**
	 * Get options for metadata query
	 *
	 * @param array $options additional options
	 *
	 * @return array
	 * @see elgg_get_metadata()
	 */
	protected function getOptions(array $options = []): array {
		$defaults = [
			'type' => 'object',
			'subtype' => 'widget',
			'limit' => 100,
			'batch' => true,
			'metadata_name_value_pairs' => [
				[
					'value' => '["%"]',
					'operand' => 'LIKE',
					'case_sensitive' => false,
				],
			],
		];
		
		return array_merge($defaults, $options);
	}
}
