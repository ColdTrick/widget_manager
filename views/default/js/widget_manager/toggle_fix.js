define(['elgg', 'jquery'], function (elgg, $) {
	console.log('fix loaded');
	$(document).on('click', '.widget-manager-fix', function (event) {
		$(this).toggleClass('elgg-state-active');
		var guid = $(this).attr('href').replace('#', '');

		elgg.action('widget_manager/widgets/toggle_fix', {
			data: {
				guid: guid
			}
		});
		event.stopPropagation();
	});
});