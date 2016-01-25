define(['jquery', 'elgg'], function ($, elgg) {
	$(document).on('submit', '.widget-user-search-form', function(){
		var guid = $(this).attr('rel');
		$('#elgg-widget-content-' + guid).load(elgg.normalize_url('ajax/view/widgets/user_search/content?guid=' + guid + '&' + $(this).serialize()));
		return false;
	});
});
