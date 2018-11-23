require(['elgg', 'jquery', 'elgg/lightbox', 'elgg/widgets'], function(elgg, $, lightbox) {


	$(document).on('submit', '.elgg-form-widgets-save', function(event) {
		event.preventDefault();
		
		var data = $(this).serialize();
		var guid = $(this).find('[name="guid"]').val();

		lightbox.close();
		
		var $widgetContent = $('#elgg-widget-content-' + guid);

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);

		elgg.action('widgets/save', {
			data: data,
			success: function (json) {
				$widgetContent.html(json.output.content);
				if (typeof (json.output.title) != "undefined") {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					$widgetTitle.html(json.output.title);
				}
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
	});
	
	elgg.register_hook_handler('init', 'system', function() {
		$('.widgets-top-row .elgg-widgets').bind({
			sortstart: function () {
				$('.widgets-top-row .elgg-widgets:last-child').addClass('elgg-state-active');
			},
			sortstop: function () {
				$('.widgets-top-row .elgg-widgets:last-child').removeClass('elgg-state-active');
			}
		});
	});
});