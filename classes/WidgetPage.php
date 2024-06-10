<?php

/**
 * Widget page
 *
 * @property string $title            title of the widget page
 * @property string $description      description of the widget page
 * @property bool   $show_description should the description be shown on the widget page
 * @property string $layout           the layout to use on the widget page
 * @property string $url              URL to the widget page
 */
class WidgetPage extends ElggObject {
	
	const SUBTYPE = 'widget_page';
	const MANAGER_RELATIONSHIP = 'manager';
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['owner_guid'] = elgg_get_site_entity()->guid;
		$this->attributes['container_guid'] = elgg_get_site_entity()->guid;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function canEdit(int $user_guid = 0): bool {
		$user_guid = $user_guid ?: elgg_get_logged_in_user_guid();
		if (in_array($user_guid, $this->getManagers())) {
			return true;
		}
		
		return parent::canEdit($user_guid);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		return elgg_normalize_url((string) $this->url);
	}
	
	/**
	 * Adds a manager relationship for this page
	 *
	 * @param \ElggUser $user Manager
	 *
	 * @return bool
	 */
	public function addManager(\ElggUser $user): bool {
		return $user->addRelationship($this->guid, self::MANAGER_RELATIONSHIP);
	}
	
	/**
	 * Removes a manager relationship for this page
	 *
	 * @param \ElggUser $user Manager
	 *
	 * @return bool
	 */
	public function removeManager(\ElggUser $user): bool {
		return $user->removeRelationship($this->guid, self::MANAGER_RELATIONSHIP);
	}
	
	/**
	 * Replaces existing managers with a set of new managers
	 *
	 * @param array $guids new managers
	 *
	 * @return void
	 */
	public function setManagers(array $guids = []): void {
		$current = $this->getManagers();
		foreach ($current as $guid) {
			if (!in_array($guid, $guids)) {
				$user = get_user((int) $guid);
				if ($user) {
					$this->removeManager($user);
				}
			}
		}
		
		$new_managers = array_diff($guids, $current);
		foreach ($new_managers as $guid) {
			$user = get_user((int) $guid);
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
	public function getManagers(): array {
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
	public function getNumColumns(): int {
		return count(explode('|', $this->layout));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName(): string {
		if (empty($this->title)) {
			return ucwords(str_replace('_', ' ', $this->url));
		}
		
		return parent::getDisplayName();
	}
}
