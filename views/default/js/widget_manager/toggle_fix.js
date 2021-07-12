define(['jquery', 'elgg/Ajax'], function ($, Ajax) {
	var ajax = new Ajax(false);
	
	$(document).on('click', '.widget-manager-fix', function (event) {
		event.stopPropagation();
		
		$(this).toggleClass('elgg-state-active');
		var guid = $(this).attr('href').replace('#', '');

		ajax.action('widget_manager/widgets/toggle_fix', {
			data: {
				guid: guid
			}
		});
	});
});
