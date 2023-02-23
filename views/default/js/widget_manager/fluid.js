define(['jquery', 'elgg/Ajax', 'muuri', 'elgg/widgets'], function ($, Ajax, Muuri) {
	
	var grid;
	var grid_selector = '.widgets-fluid-columns #elgg-widget-col-1';
	var grid_options = {
		items: '.elgg-module-widget',
		dragEnabled: false,
		dragPlaceholder: {
			enabled: true,
		},
		itemPlaceholderClass: 'fluid-placeholder',
		layoutDuration: 0,
		showDuration: 0,
		layoutOnInit: false,
		dragHandle: '.elgg-widget-handle',
		dragStartPredicate: {
			distance: 10,
			delay: 10
		},
	};
	
	if ($('.widgets-fluid-columns.elgg-layout-can-edit').length) {
		grid_options.dragEnabled = true;
	}
	
	var ajax = new Ajax();
	
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

		var $widgets = $elem.find('.elgg-module-widget');
		if (container_width > 1200) {
			$widgets.css('width', 'calc(33% - 28px)');
		} else if (container_width > 800) {
			$widgets.css('width', 'calc(50% - 28px)');
		} else {
			$widgets.css('width', 'calc(100% - 28px)');
		}
	};
	
	function gridcheck() {
		if (grid) {
			setTimeout(function() {
				$(grid_selector).each(function() {
					setItemSizes($(this));
					grid.refreshItems().layout();
				});
			}, 200);
		}
	};
	
	function initGrid() {
		setTimeout(function() {
			// added bit of delay to allow images to load (also required for correct working of delete event)
			if (grid) {
				grid.destroy();
			}
			
			setItemSizes($(grid_selector));
			
			grid = new Muuri(grid_selector, grid_options);
			
			grid.on('dragEnd', function (item, event) {
				if (event.distance === 0) {
					return;
				}
				
				// update dom with new positions
				grid.synchronize();
				
				var guids = [];
				$.each(grid.getItems(), function(index, item) {
					var guidString = $(item._element).attr('id');
					guidString = guidString.substr(guidString.indexOf('elgg-widget-') + 'elgg-widget-'.length);
					
					guids.push(guidString);
				});
				
				ajax.action('widget_manager/fluid_order', {
					data: {
						guids: guids
					}
				});
			}).on('layoutEnd', function() {
				$(grid_selector).css('visibility', 'visible');
			}).layout();
		}, 200);
	};
	
	initGrid();
		
	$(window).resize(debounce(gridcheck, 50));

	$(document).on('saveSettings', '.elgg-layout-widgets .elgg-module-widget', gridcheck);
	$(document).on('lazyLoaded', '.elgg-layout-widgets', gridcheck);
	
	$(document).on('widgetAdd widgetRemove', '.elgg-layout-widgets', initGrid);
	
	// mmenu support
	$(document).on('mmenu.toggle', gridcheck);
});
