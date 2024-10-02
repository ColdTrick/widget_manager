<?php

/**
 * Extends the feature of default ElggWidgets
 *
 * @property string $widget_manager_custom_title custom widget title
 */
class WidgetManagerWidget extends \ElggWidget {
		
	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName(): string {
		return $this->widget_manager_custom_title ?: parent::getDisplayName();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function invalidateCache(): void {
		if (!$this->guid) {
			return;
		}
		
		parent::invalidateCache();
		
		if (!\ColdTrick\WidgetManager\Widgets::isCacheableWidget($this)) {
			return;
		}
		
		$languages = elgg()->translator->getAllowedLanguages();
		foreach ($languages as $language) {
			elgg_delete_system_cache("widget_cache_{$this->guid}_{$language}");
		}
	}
}
