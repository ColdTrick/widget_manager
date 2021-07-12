define(['jquery', 'elgg', 'elgg/Ajax', 'elgg/widgets'], function($, elgg, Ajax, widgets) {

	var ajax = new Ajax(false);
	
	$(document).on('keyup', '#widget_manager_widgets_search input[type="text"]', function() {
		var $container = $('.elgg-widgets-add-panel');
		var $items = $container.find('> .elgg-body > ul > li');
		var q = $(this).val();

		if (q === '') {
			$items.show();
		} else {
			$items.hide();
			$items.filter(function () {
				return $(this).text().toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}).show();
		}
	});

	$(document).ajaxSuccess(function (e, xhr, settings) {
		if (settings.url === elgg.normalize_url('/action/widgets/add')) {
			// move new widget to a new position (after fixed widgets) if needed
			if ($(this).find('.elgg-widgets > .elgg-state-fixed').size() > 0) {
				var $widget = $(this).find('.elgg-module-widget:first');
				$widget.insertAfter($(this).find('.elgg-widgets > .elgg-state-fixed:last'));

				// first item is the recently moved widget, because fixed widgets are not part of the sortable
				var index = $(this).find('.elgg-module-widget').index($widget);
				var guidString = $widget.attr('id');
				guidString = guidString.substr(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);

				ajax.action('widgets/move', {
					data: {
						widget_guid: guidString,
						column: 1,
						position: index
					}
				});
			}
		}
	});
});
