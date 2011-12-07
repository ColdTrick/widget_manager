<?php ?>
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
});