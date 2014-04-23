<?php
?>
//<script>

elgg.widget_manager.init_widget_settings = function() {
	$(".elgg-form-widgets-save").live("submit", function(event) {
		elgg.ui.lightbox.close();
		
		var widget_id = $(this).find("input[name='guid']").val();
		var $widgetContent = $("#elgg-widget-content-" + widget_id);

		// stick the ajax loader in there
		var $loader = $('#elgg-widget-loader').clone();
		$loader.attr('id', '#elgg-widget-active-loader');
		$loader.removeClass('hidden');
		$widgetContent.html($loader);
		console.log($loader);
		
		var default_widgets = $("input[name='default_widgets']").val() || 0;
		if (default_widgets) {
			$(this).append('<input type="hidden" name="default_widgets" value="1">');
		}

		elgg.action('widgets/save', {
			data: $(this).serialize(),
			success: function(json) {
				$widgetContent.html(json.output);
				if (typeof(json.title) != "undefined") {
					var $widgetTitle = $widgetContent.parent().parent().find('.elgg-widget-title');
					$widgetTitle.html(json.title);
				}
			}
		});
		event.preventDefault();
	});
}

elgg.register_hook_handler('init', 'system', elgg.widget_manager.init_widget_settings);