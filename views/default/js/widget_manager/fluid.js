/**
 * @module widget_manager/fluid
 */
define(['elgg', 'jquery', 'widget_manager/packery', 'elgg/widgets'], function (elgg, $, Packery) {

	var options = {
		itemSelector: '.elgg-module-widget',
		percentPosition: true,
		gutter: 0,
		resize: false,
	};
	
	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	var debounce = function(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};
	
	function setItemSizes($elem) {
		var container_width = $elem.width();
		options.gutter_size = 0;
		
		var $widgets = $elem.find('.elgg-module-widget');
		$widgets.removeClass('no-margin');
		if (container_width > 1200) {
			$widgets.css('width', 'calc(33% - 16px)');
			$widgets.addClass('no-margin');
			options.gutter = 28;
		} else if (container_width > 800) {
			$widgets.css('width', 'calc(50% - 16px)');
			$widgets.addClass('no-margin');
			options.gutter = 28;
		} else {
			$widgets.css('width', '100%');
		}
	};
	
	function gridcheck() {
		$('.widgets-fluid-columns #elgg-widget-col-1').each(function() {
			setItemSizes($(this));
			var grid = new Packery(this, options);
		});
	};
	
	function initWidgets() {
		$('.widgets-fluid-columns #elgg-widget-col-1').each(function() {
			// make all items draggable
			var $items = $(this).find('.elgg-module-widget').draggable();
			
			setItemSizes($(this));
			
			var grid = new Packery(this, options);
			grid.on('dragItemPositioned', function(draggedItem ) {
				
				var $widget = $(draggedItem.element);
				var guidString = $widget.attr('id');
				guidString = guidString.substr(guidString.indexOf('elgg-widget-') + "elgg-widget-".length);
				
				elgg.action('widgets/move', {
					data: {
						widget_guid: guidString,
						column: 1,
						position: $(grid.getItemElements()).index($widget)
					}
				});
			});
			
			// bind drag events to Packery
			grid.bindUIDraggableEvents($items);
		});
	};
	
	initWidgets();
	
	$(window).resize(debounce(gridcheck, 50));
	
	$(document).on('saveSettings collapseToggle', '.elgg-layout-widgets .elgg-module-widget', gridcheck);
		
	$(document).on('lazyLoaded widgetRemove', '.elgg-layout-widgets', gridcheck);

	$(document).on('widgetAdd', '.elgg-layout-widgets', initWidgets);
});
