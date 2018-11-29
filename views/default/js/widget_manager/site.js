require(['elgg', 'jquery', 'elgg/lightbox', 'elgg/Ajax', 'elgg/widgets'], function(elgg, $, lightbox, Ajax) {


	$(document).on('submit', '.elgg-form-widgets-save', function(event) {
		event.preventDefault();
		
		var ajax = new Ajax(false);
		
		var guid = $(this).find('[name="guid"]').val();

		lightbox.close();
		
		var $widgetContent = $('#elgg-widget-content-' + guid);

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
					$widgetTitle.html(result.title);
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