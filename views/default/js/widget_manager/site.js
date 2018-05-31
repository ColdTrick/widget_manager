require(['elgg', 'jquery', 'elgg/widgets'], function(elgg, $) {

	elgg.register_hook_handler('toggle', 'menu_item', function(type, subtype, params) {
		if (!params.menu.hasClass('elgg-menu-widget-toggle')) {
			return;
		}
		
		var $widget = params.itemClicked.closest('.elgg-module-widget');
		if (params.itemClicked.hasClass('elgg-menu-item-collapse')) {
			$widget.addClass('elgg-state-collapsed');
		} else {
			$widget.removeClass('elgg-state-collapsed');
		}
	});
	
	elgg.register_hook_handler('init', 'system', function() {
		$('.widgets-top-row .elgg-widgets').bind({
			sortstart: function () {
				$('.widgets-top-row .elgg-widgets:last-child').addClass('elgg-state-active');
			},
			sortstop: function () {
				$('.widgets-top-row .elgg-widgets:last-child').removeClass('elgg-state-active');
			}
		});
	});
});