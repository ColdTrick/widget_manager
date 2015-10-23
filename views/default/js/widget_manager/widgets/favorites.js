define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');

	$(document).on('click', '.elgg-menu-item-widget-favorites a', function (e) {
		e.preventDefault();
		var $elem = $(this);
		elgg.action($elem.attr("href"), {
			data: {
				title: document.title
			},
			success: function (data) {
				$elem.replaceWith(data.output);
			}
		});
	});

	$(document).on('click', '.widgets-favorite-entity-delete', function (e) {
		e.preventDefault();
		var $elem = $(this);
		elgg.action($elem.attr("href"), {
			success: function () {
				$elem.parent().hide();
			}
		});
	});

});