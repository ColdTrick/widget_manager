require(['jquery', 'elgg', 'elgg/Ajax', 'elgg/widgets'], function($, elgg, Ajax) {
	
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
		
		$widget.trigger({
			type: 'collapseToggle',
			widget: $widget
		});
	});
	
	if ($('.elgg-module-widget.lazy-loading').length) {
		$('.elgg-layout-widgets').each(function(i, layout) {
			var $lazy_widgets = $(layout).find('.elgg-module-widget.lazy-loading');
			if (!$lazy_widgets.length) {
				return;
			}
			
			var guids = [];
			
			$lazy_widgets.each(function(index, item) {
				var guidString = $(item).attr('id');
				guids.push(guidString.substr(guidString.indexOf('elgg-widget-') + 'elgg-widget-'.length));
			});
			
			var ajax = new Ajax(false);
			ajax.action('widget_manager/lazy_load_widgets', {
				data: {
					guids: guids,
					page_owner_guid: $(layout).data().pageOwnerGuid,
					context_stack: $(layout).data().contextStack,
				},
				success: function (result) {
					$.each(result, function(guid, body) {
					    $('#elgg-widget-' + guid + ' > .elgg-body').html(body);
					});
					
					$(layout).trigger({
						type: 'lazyLoaded',
						layout: $(layout)
					});
				}
			});	
		});			
	}
});