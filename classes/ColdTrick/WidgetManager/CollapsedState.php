<?php

namespace ColdTrick\WidgetManager;

/**
 * Handles collapsed state handling for entities
 */
trait CollapsedState {
	
	static $COLLAPSED_CACHE_NAME = 'collapsed_cache';

	/**
	 * Store collapse preference for a user
	 *
	 * @param int $user_guid guid of the user. Defaults to logged in user
	 *
	 * @return boolean
	 */
	public function collapse($user_guid = 0) {
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		$user = get_entity($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}
			
		$user->addRelationship($this->guid, 'widget_state_collapsed');
		$user->removeRelationship($this->guid, 'widget_state_open');
		
		$this->flushCollapsedCache();
		
		return true;
	}
	
	/**
	 * Store expand preference for a user
	 *
	 * @param int $user_guid guid of the user. Defaults to logged in user
	 *
	 * @return boolean
	 */
	public function expand($user_guid = 0) {
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		$user = get_entity($user_guid);
		if (!$user instanceof \ElggUser) {
			return false;
		}
			
		$user->addRelationship($this->guid, 'widget_state_open');
		$user->removeRelationship($this->guid, 'widget_state_collapsed');
		
		$this->flushCollapsedCache();
		
		return true;
	}
	
	/**
	 * Checks if the logged in user has a open or closed collapsed state relationship with the entity
	 *
	 * @param string $state state to check
	 *
	 * @return bool
	 */
	public function checkCollapsedState($state) {
		$user_guid = elgg_get_logged_in_user_guid();
		if (empty($user_guid)) {
			return false;
		}
		
		$collapsed_widgets_state = $this->getCollapsedCache($user_guid);
				
		if (!array_key_exists($this->guid, $collapsed_widgets_state)) {
			return false;
		}
		
		if (in_array($state, $collapsed_widgets_state[$this->guid])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns (optionally cached) all users widget states
	 *
	 * @param int $user_guid guid of the user
	 *
	 * @return array
	 */
	protected function getCollapsedCache($user_guid) {
		$return = elgg()->session->get(self::$COLLAPSED_CACHE_NAME);
		
		if ($return !== null) {
			return $return;
		}
			
		$return = [];
					
		$rels = elgg_get_relationships([
			'relationship_guid' => $user_guid,
			'relationship' => ['widget_state_collapsed', 'widget_state_open'],
			'limit' => false,
		]);

		foreach ($rels as $rel) {
			if (!isset($return[$rel->guid_two])) {
				$return[$rel->guid_two] = [];
			}
			$return[$rel->guid_two][] = $rel->relationship;
		}
		
		elgg()->session->set(self::$COLLAPSED_CACHE_NAME, $return);
		
		return $return;
	}

	protected function flushCollapsedCache() {
		elgg()->session->remove(self::$COLLAPSED_CACHE_NAME);
	}
}
