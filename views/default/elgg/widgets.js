define(['jquery', 'elgg/i18n', 'elgg/Ajax', 'elgg/lightbox', 'jquery-ui/widgets/sortable'], function ($, i18n, Ajax, lightbox) {

	var widgets = {};

	/**
	 * Persist the widget's new position
	 *
	 * @param {Object} event
	 * @param {Object} ui
	 *
	 * @return void
	 */
	widgets.move = function (event, ui) {

		// elgg-widget-<guid>
		var guidString = ui.item.attr('id');
		guidString = guidString.substr(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);

		// elgg-widget-col-<column>
		var col = ui.item.parent().attr('id');
		col = col.substr(col.indexOf('elgg-widget-col-') + "elgg-widget-col-".length);

		var ajax = new Ajax(false);
		ajax.action('widgets/move', {
			data: {
				widget_guid: guidString,
				column: col,
				position: ui.item.index()
			}
		});
	};

	/**
	 * Removes a widget from the layout
	 *
	 * Event callback the uses Ajax to delete the widget and removes its HTML
	 *
	 * @param {Object} event
	 * @return void
	 */
	widgets.remove = function (event) {
		event.preventDefault();
		
		if (confirm(i18n.echo('deleteconfirm')) === false) {
			return;
		}
		
		var $layout = $(this).closest('.elgg-layout-widgets');
		var $widget = $(this).closest('.elgg-module-widget');
		$widget.remove();

		// delete the widget through ajax
		var ajax = new Ajax(false);
		ajax.action($(this).attr('href'));
		
		$layout.trigger({
			type: 'widgetRemove',
			layout: $layout,
			widget: $widget
		});
	};

	/**
	 * Save a widget's settings
	 *
	 * Uses Ajax to save the settings and updates the HTML.
	 *
	 * @param {Object} event
	 * @return void
	 */
	widgets.saveSettings = function (event) {
		event.preventDefault();
		
		var guid = $(this).find('[name="guid"]').val();

		lightbox.close();
		
		var $widget = $('#elgg-widget-' + guid);
		var $widgetContent = $widget.find('.elgg-widget-content');

		// stick the ajax loader in there
		$widgetContent.html('<div class="elgg-ajax-loader"></div>');
		
		var ajax = new Ajax(false);
		ajax.action('widgets/save', {
			data: ajax.objectify(this),
			success: function (result) {
				$widgetContent.html(result.content);
				
				if (result.title !== '') {
					var $widgetTitle = $widget.find('.elgg-widget-title');
					
					var newWidgetTitle = result.title;
					if (result.href !== '') {
						newWidgetTitle = "<a href='" + result.href + "' class='elgg-anchor'><span class='elgg-anchor-label'>" + newWidgetTitle + "</span></a>";
					}
					
					$widgetTitle.html(newWidgetTitle);
				}
				
				$widget.trigger({
					type: 'saveSettings',
					widget: $widget
				});
			}
		});
	};
	
	$('.elgg-layout-widgets:not(.widgets-fluid-columns)').find('.elgg-widgets').each(function() {
		
		var opts = $(this).data().sortableOptions;
		var defaults = {
			items: 'div.elgg-module-widget.elgg-state-draggable',
			connectWith: '.elgg-widgets',
			handle: '.elgg-widget-handle',
			forcePlaceholderSize: true,
			placeholder: 'elgg-widget-placeholder',
			opacity: 0.8,
			revert: 500,
			stop: widgets.move
		};
		var settings = $.extend({}, defaults, opts);
		
		$(this).sortable(settings);
	});

	$(document).on('click', 'a.elgg-widget-delete-button', widgets.remove);
	$(document).on('submit', '.elgg-form-widgets-save', widgets.saveSettings);

	return widgets;
});

