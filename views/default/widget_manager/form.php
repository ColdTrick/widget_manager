<?php 

	$widgets = $vars["widgets"];
	$widget_context = $vars["widget_context"];
	$configured_widgets = $vars["configured_widgets"];

	$form_body .= elgg_view("input/hidden", array("internalname" => "widget_context", "value" => $widget_context));
	
	$form_body .= elgg_view("widget_manager/forms/placement", array("widgets" => $widgets, "widget_context" => $widget_context, "configured_widgets" => $configured_widgets));
	$form_body .= elgg_view("widget_manager/forms/settings", array("widgets" => $widgets, "widget_context" => $widget_context));
	
	$form_body .= "<div>\n";
	$form_body .= elgg_view("input/submit", array("value" => elgg_echo("save")));
	$form_body .= "</div>\n";
	
	$form = elgg_view("input/form", array("body" => $form_body,
											"action" => $vars["url"] . "action/widget_manager/manage",
											"internalid" => "widget_manager_manage_form"
	));

?>
<script type="text/javascript">

	$(document).ready(function(){
	
		$('#widget_manager_manage_placement_gallery_widgets, #widget_manager_manage_placement_widgets .widget_manager_placement_widget_column').sortable({
			connectWith: [".widget_manager_placement_widget_column"],
			items: '.widget_manager_widget_wrapper',
			handle: '.widget_manager_widget_move',
			placeholder: 'widget_manager_widget_placeholder',
			appendTo: '#widget_manager_manage_placement_widgets',
			update: function(e, ui){
				if($(this).attr('id') != 'widget_manager_manage_placement_gallery_widgets'){
					widget_manager_update_placement($(this).attr('id'));
				}
			},
			remove: function(e, ui){
				if($(this).attr('id') == 'widget_manager_manage_placement_gallery_widgets'){
					$(this).append($(ui.item).clone().attr('style', ''));
				}
			}
		});
	
		$('#widget_manager_manage_placement_gallery_widgets').droppable({
			accept: '.widget_manager_widget_wrapper',
			hoverClass: 'droppable-hover',
			tolerance: 'intersect',
			drop: function(e, ui){
				$(ui.draggable).remove();
			}	
		});
		
		$('#widget_manager_manage_placement_widgets .widget_manager_placement_widget_column').droppable({
			accept: '.widget_manager_widget_wrapper',
			hoverClass: 'droppable-hover',
			tolerance: 'intersect'
		});
	});
	
	function widget_manager_form_navigate(element){
	
		if($(element).parent('li.selected').length == 0){
			$('#elgg_horizontal_tabbed_nav li').toggleClass('selected');
			$('#widget_manager_manage_form .widget_manager_manage').toggle();
		}
	}
	
	function widget_manager_update_placement(column_id){
		var handlers = $('#' + column_id + ' input[type="hidden"][name="widget_handler[]"]').makeDelimitedList('value');
	
		$('#widget_manager_manage_placement_widgets input[type="hidden"][name="' + column_id + '"]').val(handlers);
	}
	
</script>

<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li class="selected"><a onclick="widget_manager_form_navigate(this);" href="javascript:void(0);"><?php echo elgg_echo("widget_manager:manage:form:nav:placement"); ?></a></li>
			<li><a onclick="widget_manager_form_navigate(this);" href="javascript:void(0);"><?php echo elgg_echo("widget_manager:manage:form:nav:settings"); ?></a></li>
		</ul>
	</div>
	
	<?php echo $form; ?>
</div>