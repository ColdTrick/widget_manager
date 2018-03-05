require(['elgg', 'jquery', 'elgg/widgets'], function(elgg, $) {

	$(document).on('click', '.elgg-module-widget .elgg-menu-item-collapse a', function (event) {
		if (elgg.is_logged_in()) {
			var collapsed = 1;
			if ($(this).hasClass("elgg-widget-collapsed")) {
				collapsed = 0;
				// elgg changes collapsed class after this click event
			}

			var guid = $(this).attr("href").replace("#elgg-widget-content-", "");

			elgg.action('widget_manager/widgets/toggle_collapse', {
				data: {
					collapsed: collapsed,
					guid: guid
				}
			});
		}
	});
	
	elgg.register_hook_handler('init', 'system', function() {
		$('.layout-widgets-groups .elgg-widgets').bind({
			sortstart: function () {
				$('#elgg-widget-col-3').addClass('elgg-state-active');
			},
			sortstop: function () {
				$('#elgg-widget-col-3').removeClass('elgg-state-active');
			}
		});
	});
});