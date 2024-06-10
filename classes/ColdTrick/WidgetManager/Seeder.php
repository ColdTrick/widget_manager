<?php

namespace ColdTrick\WidgetManager;

use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

/**
 * Database seeder for widget pages
 */
class Seeder extends Seed {
	
	protected array $layouts = [
		'33|33|33',
		'50|25|25',
		'25|50|25',
		'25|25|50',
		'75|25',
		'60|40',
		'50|50',
		'40|60',
		'25|75',
		'100',
	];
	
	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());
		
		$site = elgg_get_site_entity();
		
		while ($this->getCount() < $this->limit) {
			try {
				/* @var $entity \WidgetPage */
				$entity = $this->createObject([
					'subtype' => \WidgetPage::SUBTYPE,
					'owner_guid' => $site->guid,
					'container_guid' => $site->guid,
					'layout' => $this->getRandomLayout(),
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create with the given options
				continue;
			}
			
			unset($entity->description);
			
			$entity->url = 'widgets-' . elgg_get_friendly_title($entity->title);
			
			$user_guids = [];
			for ($i = 0; $i < $this->faker()->numberBetween(0, 5); $i++) {
				$user = $this->getRandomUser($user_guids);
				$user_guids[] = $user->guid;
				
				$entity->addManager($user);
			}
			
			$this->advance();
		}
		
		// need to invalidate the cache of widget page urls
		elgg_delete_system_cache('widget_pages');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		/* @var $entities \ElggBatch */
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => \WidgetPage::SUBTYPE,
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		
		/* @var $entity \WidgetPage */
		foreach ($entities as $entity) {
			if ($entity->delete()) {
				$this->log("Deleted widget page {$entity->guid}");
			} else {
				$this->log("Failed to delete widget page {$entity->guid}");
				$entities->reportFailure();
				continue;
			}
			
			$this->advance();
		}
		
		// need to invalidate the cache of widget page urls
		elgg_delete_system_cache('widget_pages');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getType(): string {
		return \WidgetPage::SUBTYPE;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getDefaultLimit(): int {
		return 5;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getCountOptions(): array {
		return [
			'type' => 'object',
			'subtype' => \WidgetPage::SUBTYPE,
		];
	}
	
	/**
	 * Get a random layout to add to the widget page
	 *
	 * @return string
	 */
	protected function getRandomLayout(): string {
		$key = array_rand($this->layouts);
		
		return $this->layouts[$key];
	}
}
