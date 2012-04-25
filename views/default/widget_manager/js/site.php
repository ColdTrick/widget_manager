<?php
?>
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

function widget_manager_init(){
	// reset draggable functionality to pointer
	$(".elgg-widgets").sortable("option", "tolerance", "pointer");
	
	$(".elgg-widgets").bind({
		sortstart: function(event, ui){
			$(".widget-manager-groups-widgets-top-row").addClass("widget-manager-groups-widgets-top-row-highlight");
		},
		sortstop: function(event, ui){
			$(".widget-manager-groups-widgets-top-row").removeClass("widget-manager-groups-widgets-top-row-highlight");
		}
	});
}

elgg.register_hook_handler('init', 'system', widget_manager_init);
