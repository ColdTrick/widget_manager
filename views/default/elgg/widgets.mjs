import 'jquery';
import 'jquery-ui';
import Ajax from 'elgg/Ajax';
import lightbox from 'elgg/lightbox';

/**
 * Persist the widget's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
function moveWidget(event, ui) {
	// elgg-widget-<guid>
	var guidString = ui.item.attr('id');
	guidString = guidString.substring(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);

	var ajax = new Ajax(false);
	ajax.action('widgets/move', {
		data: {
			widget_guid: guidString,
			column: ui.item.parent().data('widgetColumn'),
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
function removeWidget(event) {
	event.preventDefault();

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
function saveWidgetSettings(event) {
	event.preventDefault();
	
	var guid = $(this).find('[name="guid"]').val();
	var $widget = $('#elgg-widget-' + guid);
	var $widgetContent = $widget.find('.elgg-widget-content');

	var ajax = new Ajax();
	ajax.action('widgets/save', {
		data: ajax.objectify(this),
		success: function (result) {
			lightbox.close();
			
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
		items: '.elgg-module-widget.elgg-state-draggable',
		connectWith: '.elgg-widgets',
		handle: '.elgg-widget-handle',
		forcePlaceholderSize: true,
		placeholder: 'elgg-widget-placeholder',
		opacity: 0.8,
		revert: 500,
		stop: moveWidget
	};
	var settings = $.extend({}, defaults, opts);
	
	$(this).sortable(settings);
});

// regular layouts
$(document).on('click', '.elgg-menu-title-widgets .elgg-menu-item-hide-widget-contents a, .elgg-menu-title-widgets .elgg-menu-item-show-widget-contents a', function() {
	var $layout = $(this).closest('.elgg-layout-widgets');
	$layout.find('.elgg-menu-item-hide-widget-contents, .elgg-menu-item-show-widget-contents').toggleClass('hidden');
	
	$layout.toggleClass('elgg-widgets-hide-content');
	
	return false;
});

// widget page layouts with a title menu
$(document).on('click', '.elgg-menu-title .elgg-menu-item-hide-widget-contents a, .elgg-menu-title .elgg-menu-item-show-widget-contents a', function() {
	$('.elgg-menu-title').find('.elgg-menu-item-hide-widget-contents, .elgg-menu-item-show-widget-contents').toggleClass('hidden');
	
	$('.elgg-layout-widgets').toggleClass('elgg-widgets-hide-content');
	
	return false;
});

$(document).on('click', 'a.elgg-widget-delete-button', removeWidget);
$(document).on('submit', '.elgg-form-widgets-save', saveWidgetSettings);
