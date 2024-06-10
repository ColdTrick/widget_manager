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
}
