<?php ?>
// add a custom case-insensitive Contains function for widget filter (jQuery > 1.3)
jQuery.expr[':'].Contains = function(a,i,m){
     return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function widget_manager_widgets_search(q){
	if(q === ""){
		$("#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper").show();
	} else {
		$("#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper").hide();
		$("#widget_manager_widgets_select .widget_manager_widgets_lightbox_wrapper:Contains('" + q + "')").show();
	}
}

function saveWidgetOrder(){
	var widgetNamesLeft = outputWidgetList('#widgets_left');
	var widgetNamesMiddle = outputWidgetList('#widgets_middle');
	var widgetNamesRight = outputWidgetList('#widgets_right');
	var widgetNamesTop = outputWidgetList('#widgets_top');

	$('#debugField1').val(widgetNamesLeft);
	$('#debugField2').val(widgetNamesMiddle);
	$('#debugField3').val(widgetNamesRight);
	$('#debugField4').val(widgetNamesTop);
	
	$.post('<?php echo $vars['url'];?>action/widgets/reorder', $("form[action='<?php echo $vars['url'];?>action/widgets/reorder']").serialize());
}

function IsNumeric(sText){
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber === true; i++){ 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) === -1){
         IsNumber = false;
         }
      }
	return IsNumber;
}

// widget toggle
function toggleWidgetEdit() {
	$(this).parents('div.collapsable_box').children("[class=collapsable_box_editpanel]").slideToggle("fast");
	return false;
}

// Toggle widgets contents and save to a cookie
function toggleWidgetContent() {
	var targetContent = $(this).parents('div.collapsable_box').children('div.collapsable_box_content');
	
	if (targetContent.css('display') === 'none') {
		targetContent.slideDown(400);
		$(this).html('-');
		$(this).parents('div.collapsable_box').find("a.toggle_box_edit_panel").fadeIn('medium');
		
		// set cookie for widget panel open-state
		var thisWidgetName = $(this).parents('div.collapsable_box').parent().attr('id');
		$.cookie(thisWidgetName, 'expanded', { expires: 365 });
		
	} else {
		targetContent.slideUp(400);
		$(this).html('+');
		$(this).parents('div.collapsable_box').find("a.toggle_box_edit_panel").fadeOut('medium');
		// make sure edit pane is closed
		$(this).parents('div.collapsable_box').children("[class=collapsable_box_editpanel]").hide();
		
		// set cookie for widget panel closed-state
		var thisWidgetName = $(this).parents('div.collapsable_box').parent().attr('id');
		$.cookie(thisWidgetName, 'collapsed', { expires: 365 });			
	}

	return false;
}

$(document).ready(function(){
	// Widget Manager More Info tooltips
	$("span.widget_manager_more_info").hover(
		function(e) {
			var tooltip = $("#text_" + $(this).attr('id'));
			$("body").append("<p id='widget_manager_more_info_tooltip'>"+ $(tooltip).html() + "</p>");
		
			if (e.pageX < 900) {
				$("#widget_manager_more_info_tooltip")
					.css("top",(e.pageY + 10) + "px")
					.css("left",(e.pageX + 10) + "px")
					.fadeIn("medium");	
			}	
			else {
				$("#widget_manager_more_info_tooltip")
					.css("top",(e.pageY + 10) + "px")
					.css("left",(e.pageX - 260) + "px")
					.fadeIn("medium");		
			}			
		},
		function() {
			$("#widget_manager_more_info_tooltip").remove();
		}
	);
	
	// COLLAPSABLE WIDGETS (on Dashboard & Profile pages); Could not use default mechanisme because of different layout
	// toggle widget box contents
	$('#layout_canvas a.toggle_box_contents').unbind('click', toggleContent).bind('click', toggleWidgetContent);
	$('#layout_canvas a.toggle_box_edit_panel').unbind('click').bind('click', toggleWidgetEdit);	
});