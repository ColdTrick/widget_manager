var widget_manager_multi_dashboard_dropped = false;

$(document).ready(function() {

	// edit dashboard link
	$("#widget-manager-multi-dashboard-tabs .widget-manager-multi-dashboard-tabs-edit").click(function(event) {
		$.colorbox({
			href : $(this).data().multiDashboardEditLink,
			innerWidth: 400
		});
		event.preventDefault();
	});

	// adds the ability to move widgets between dashboards
	$('#widget-manager-multi-dashboard-tabs .widget-manager-multi-dashboard-tab-widgets').not('.elgg-state-selected').droppable({
		accept: '.elgg-module-widget',
		activeClass: 'widget-manager-multi-dashboard-tab-active',
		hoverClass: 'widget-manager-multi-dashboard-tab-hover',
		tolerance: 'pointer',
		drop: function(event, ui) {
			
			// elgg-widget-<guid>
			var guidString = ui.draggable.attr('id');
			guidString = guidString.substr(guidString.indexOf('elgg-widget-') + 'elgg-widget-' . length);

			// tab guid
			var tabGuid = $(this).find('a:first').attr('rel');
			if (tabGuid == 'nofollow') {
				tabGuid = 0;
			}

			ui.draggable.hide();

			// prevent the widget from being moved
			widget_manager_multi_dashboard_sort_stop = $('.elgg-widgets').sortable('option', 'stop');
			$('.elgg-widgets').sortable('option', 'stop', widget_manager_restore_sort_stop);
			
			elgg.action('multi_dashboard/drop', {
				data: {
					widget_guid: guidString,
					multi_dashboard_guid: tabGuid
				},
				success: function(){
					ui.draggable.remove();
				},
				error: function(){
					ui.draggable.show();
				}
			});
		}
	});

	$('#widget-manager-multi-dashboard-tabs').sortable({
		items: 'li.widget-manager-multi-dashboard-tab',
		tolerance: 'pointer',
		axis: 'x',
		cursor: 'move',
		distance: 5,
		delay: 15,
		forcePlaceholderSize: true,
		update: function(event, ui) {
			$order = $(this).sortable('toArray');
			
			elgg.action('multi_dashboard/reorder', {
				data: {
					order: $order
				}
			});
		}
	});
});

function widget_manager_restore_sort_stop() {
	$('.elgg-widgets').sortable('option', 'stop', widget_manager_multi_dashboard_sort_stop);
}

function widget_manager_change_dashboard_type(elem){
	switch($(elem).val()){
		case 'iframe':
			$('#widget_manager_multi_dashboard_edit .widget-manager-multi-dashboard-types-widgets').addClass('hidden');
			$('#widget_manager_multi_dashboard_edit .widget-manager-multi-dashboard-types-iframe').removeClass('hidden');

			break;
		default:
			$('#widget_manager_multi_dashboard_edit .widget-manager-multi-dashboard-types-iframe').addClass('hidden');
			$('#widget_manager_multi_dashboard_edit .widget-manager-multi-dashboard-types-widgets').removeClass('hidden');
		
			break;
	}
}