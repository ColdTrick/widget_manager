import 'jquery';
import i18n from 'elgg/i18n';
import Ajax from 'elgg/Ajax';

$(document).on('click', '.widget-manager-unsupported-context .elgg-input-checkbox', function (e, elem) {
	if (!$(this).is(':checked')) {
		return;
	}
	
	if (!confirm(i18n.echo('widget_manager:forms:manage_widgets:unsupported_context:confirm'))) {
		return false;
	}
});

$(document).on('change', '.elgg-input-checkbox[name^="widgets_config"]', function (e, elem) {
	var ajax = new Ajax();
	
	ajax.action('widget_manager/manage_widgets', {
		data: {
			[$(this).attr('name')]: $(this).is(':checked') ? 1 : 0
		},
		showSuccessMessages: false
	});
});
