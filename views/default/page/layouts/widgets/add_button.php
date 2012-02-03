<?php
/**
 * Button area for showing the add widgets panel
 */

?>
<div class="elgg-widget-add-control">
<?php
	if(elgg_in_context("dashboard") && widget_manager_multi_dashboard_enabled()){
		$options = array(
				"type" => "object",
				"subtype" => MultiDashboard::SUBTYPE,
				"owner_guid" => elgg_get_logged_in_user_guid(),
				"count" => true
			);
		$tab_count = elgg_get_entities($options);
		
		if($tab_count < MULTI_DASHBOARD_MAX_TABS){
			echo elgg_view('output/url', array(
				'id' => 'widget-manager-multi-dashboard',
				'href' => 'multi_dashboard/edit',
				'text' => elgg_echo('widget_manager:multi_dashboard:add'),
				'class' => 'elgg-button elgg-button-action',
				'is_trusted' => true,
			));
			echo " ";
		}
	}
	if(!elgg_in_context("iframe_dashboard")){
		echo elgg_view('output/url', array(
			'id' => 'widgets-add-panel',
			'href' => '#widget_manager_widgets_select',
			'text' => elgg_echo('widgets:add'),
			'class' => 'elgg-button elgg-button-action',
			'is_trusted' => true,
		));
	}
?>
</div>
<script type="text/javascript">

	$(document).ready(function(){
		$("#widget-manager-multi-dashboard").fancybox({ 
			autoDimensions: false, 
			width: 400, 
			height: 350
		});	
	});
</script>