// regular layouts
$(document).on('click', '.elgg-menu-title-widgets .elgg-menu-item-hide-widget-contents a, .elgg-menu-title-widgets .elgg-menu-item-show-widget-contents a', function() {
	var $layout = $(this).closest('.elgg-layout-widgets');
	$layout.find('.elgg-menu-item-hide-widget-contents, .elgg-menu-item-show-widget-contents').toggleClass('hidden');
	
	$layout.toggleClass('elgg-widgets-hide-content');
	
	return false;
});

// widget page layouts with a title menu
$(document).on('click', '.elgg-menu-title .elgg-menu-item-hide-widget-contents a, .elgg-menu-title .elgg-menu-item-show-widget-contents a', function() {
	$('.elgg-menu-title').find('.elgg-menu-item-hide-widget-contents, .elgg-menu-item-show-widget-contents').toggleClass('hidden');
	
	$('.elgg-layout-widgets').toggleClass('elgg-widgets-hide-content');
	
	return false;
});
