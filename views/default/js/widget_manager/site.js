require(['elgg', 'jquery', 'elgg/lightbox', 'elgg/Ajax', 'elgg/widgets'], function(elgg, $, lightbox, Ajax) {
	$(document).on('submit', '.elgg-form-widgets-save', function(event) {
		event.preventDefault();
		
		var ajax = new Ajax(false);
		
		var guid = $(this).find('[name="guid"]').val();

		lightbox.close();
		
		var $widget = $('#elgg-widget-' + guid);
		var $widgetContent = $widget.find('.elgg-widget-content');

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);

		ajax.action('widgets/save', {
			data: ajax.objectify(this),
			success: function (result) {
				$widgetContent.html(result.content);
				if (typeof (result.title) != "undefined") {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					
					var newWidgetTitle = result.title;
					if (typeof (result.href) != "undefined") {
						newWidgetTitle = "<a href='" + result.href + "' class='elgg-anchor'><span class='elgg-anchor-label'>" + newWidgetTitle + "</span></a>";
					}
					
					$widgetTitle.html(newWidgetTitle);
				}
				
				$widget.trigger({
					type: 'saveSettings',
					widget: $widget
				});
			}
		});
	});
	
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
	
	$('.widgets-top-row .elgg-widgets').bind({
		sortstart: function () {
			$('.widgets-top-row .elgg-widgets:last-child').addClass('elgg-state-active');
		},
		sortstop: function () {
			$('.widgets-top-row .elgg-widgets:last-child').removeClass('elgg-state-active');
		}
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