/**
 * @module elgg/widgets
 */
define(['elgg', 'jquery', 'elgg/ready'], function (elgg, $) {

	var widgets = {};

	/**
	 * Widgets initialization
	 *
	 * @return void
	 */
	widgets.init = function () {};

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

		elgg.action('widgets/move', {
			data: {
				widget_guid: guidString,
				column: col,
				position: ui.item.index()
			}
		});

		// @hack fixes jquery-ui/opera bug where draggable elements jump
		ui.item.css('top', 0);
		ui.item.css('left', 0);
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
		if (confirm(elgg.echo('deleteconfirm')) === false) {
			event.preventDefault();
			return;
		}
		
		event.preventDefault();
		
		var $layout = $(this).closest('.elgg-layout-widgets');
		var $widget = $(this).closest('.elgg-module-widget');
		$widget.remove();

		// delete the widget through ajax
		elgg.action($(this).attr('href'));
		
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
		var $widget = $(this).closest('.elgg-module-widget');
		var $widgetContent = $widget.find('.elgg-widget-content');

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);
		
		elgg.action('widgets/save', {
			data: $(this).serialize(),
			success: function (json) {
				$widgetContent.html(json.output.content);
				if (typeof (json.output.title) != "undefined") {
					var $widgetTitle = $widget.find('.elgg-widget-title');
					
					var newWidgetTitle = json.output.title;
					if (typeof (json.output.href) != "undefined") {
						newWidgetTitle = "<a href='" + json.output.href + "' class='elgg-anchor'><span class='elgg-anchor-label'>" + newWidgetTitle + "</span></a>";
					}
					
					$widgetTitle.html(newWidgetTitle);
				}
				
				$widget.trigger('saveSettings', {
					widget: $widget
				});
			}
		});
		
		event.preventDefault();
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
	$(document).on('submit', '.elgg-widget-edit > form ', widgets.saveSettings);

	return widgets;
});

