<?php
/**
 * Button area for showing the add widgets panel
 */

?>
<div class="elgg-widget-add-control">
<?php
	if(elgg_in_context("dashboard") && widget_manager_multi_dashboard_enabled()){
		echo elgg_view('output/url', array(
			'id' => 'widget-manager-multi-dashboard',
			'href' => '#widget_manager_multi_dashboard_select',
			'text' => elgg_echo('widget_manager:multi_dashboard:add'),
			'class' => 'elgg-button elgg-button-action',
			'is_trusted' => true,
		));
		echo " ";
	}
	echo elgg_view('output/url', array(
		'id' => 'widgets-add-panel',
		'href' => '#widget_manager_widgets_select',
		'text' => elgg_echo('widgets:add'),
		'class' => 'elgg-button elgg-button-action',
		'is_trusted' => true,
	));
?>
</div>
<script type="text/javascript">

	$(document).ready(function(){
		$("#widget-manager-multi-dashboard").fancybox({ 
			autoDimensions: false, 
			width: 400, 
			height: 400
		});	
	});
</script>