define(['jquery'], function($) {
	
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
});
