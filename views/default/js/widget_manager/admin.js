require(['elgg', 'jquery'], function (elgg, $) {

	$(".widget-manager-fix").live("click", function (event) {
		$(this).toggleClass("fixed");
		var guid = $(this).attr("href").replace("#", "");

		elgg.action('widget_manager/widgets/toggle_fix', {
			data: {
				guid: guid
			}
		});
		event.stopPropagation();
	});

	$(document).on('click', '#widget-manager-settings-add-extra-context', function (event) {
		$("#widget-manager-settings-extra-contexts tr.hidden").clone().insertBefore($("#widget-manager-settings-extra-contexts tr.hidden")).removeClass("hidden");
	});

	$(document).on('click', '#widget-manager-settings-extra-contexts .elgg-icon-delete', function (event) {
		$(this).parent().parent().remove();
	});
});