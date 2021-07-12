define(['jquery'], function ($) {
	$(document).on('open', '.elgg-tabs-component.widget-settings .elgg-tabs > li', function() {
		$(window).trigger('resize.lightbox');
	})
});
