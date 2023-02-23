<?php
/**
 * Extends the feature of default ElggWidgets
 */
class WidgetManagerWidget extends ElggWidget {
		
	/**
	 * {@inheritDoc}
	 */
	public function getDisplayName(): string {
		return $this->widget_manager_custom_title ?: parent::getDisplayName();
	}
}
