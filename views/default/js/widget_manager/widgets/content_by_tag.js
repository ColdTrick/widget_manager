define(['jquery'], function ($) {
	
	$(document).on('change', '.elgg-form-widgets-save-content-by-tag [name="params[display_option]"]', function() {
		var $edit_form = $(this).parents('.elgg-form-widgets-save-content-by-tag');
		$edit_form.find('.widgets-content-by-tag-display-options > div').hide();
		$edit_form.find('.widgets-content-by-tag-display-options-' + $(this).val()).show();
	});
	
	init = function() {
		$('.elgg-form-widgets-save-content-by-tag [name="params[display_option]"]').change();
		$('.elgg-form-widgets-save-content-by-tag .elgg-user-picker').addClass('ui-front');
	};
});