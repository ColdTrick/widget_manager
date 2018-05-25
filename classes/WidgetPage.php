<?php

use Phinx\Migration\Manager;

class WidgetPage extends ElggObject {
	
	const SUBTYPE = 'widget_page';
	const MANAGER_RELATIONSHIP = 'manager';
	
	/**
	 * initializes the default class attributes
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['owner_guid'] = elgg_get_site_entity()->guid;
		$this->attributes['container_guid'] = elgg_get_site_entity()->guid;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::canEdit()
	 */
	public function canEdit($user_guid = 0) {
		
		if (in_array($user_guid, $this->getManagers())) {
			return true;
		}
		
		return parent::canEdit($user_guid);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		return elgg_normalize_url($this->url);
	}
	
	/**
	 * Adds a manager relationship for this page
	 * @param \ElggUser $user Manager
	 *
	 * @return bool
	 */
	public function addManager(\ElggUser $user) {
		return $user->addRelationship($this->guid, self::MANAGER_RELATIONSHIP);
	}
	
	/**
	 * Removes a manager relationship for this page
	 * @param \ElggUser $user Manager
	 *
	 * @return bool
	 */
	public function removeManager(\ElggUser $user) {
		return $user->removeRelationship($this->guid, self::MANAGER_RELATIONSHIP);
	}
	
	/**
	 * Replaces existing managers with a set of new managers
	 *
	 * @param array $guids new managers
	 *
	 * @return void
	 */
	public function setManagers($guids = []) {
		$current = $this->getManagers();
		foreach ($current as $guid) {
			if (!in_array($guid, $guids)) {
				$user = get_entity($guid);
				if ($user) {
					$this->removeManager($user);
				}
			}
		}
		
		$new_managers = array_diff($guids, $current);
		foreach ($new_managers as $guid) {
			$user = get_entity($guid);
			if ($user) {
				$this->addManager($user);
			}
		}
	}
	
	/**
	 * Returns an array of guids of the current managers
	 *
	 * @return array
	 */
	public function getManagers() {
		return (array) elgg_get_entities([
			'type' => 'user',
			'relationship' => self::MANAGER_RELATIONSHIP,
			'relationship_guid' => $this->guid,
			'inverse_relationship' => true,
			'callback' => function($row) {
				return $row->guid;
			},
		]);
	}
	
	/**
	 * Returns number of columns based on layout config
	 *
	 * @return int
	 */
	public function getNumColumns() {
		return count(explode('|', $this->layout));
	}
}
