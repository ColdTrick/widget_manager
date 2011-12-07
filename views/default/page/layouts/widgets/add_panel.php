<?php
elgg_load_js('lightbox');
elgg_load_css('lightbox');

$context = $vars["context"];

$params = array(
		'name' => 'widget_context',
		'value' => $context
);
echo elgg_view('input/hidden', $params);

?>
<script type="text/javascript">

	$(document).ready(function(){
		$("#widgets-add-panel").fancybox({ autoDimensions: false, width: 600, height: "80%"});	
	});

</script>
<?php 
	
	$widget_context = str_replace("default_", "", $context);
	
	$widgets = elgg_get_widget_types($widget_context, $vars["exact_match"]);
	widget_manager_sort_widgets($widgets);
	
	$current_handlers = array();
	foreach ($vars["widgets"] as $column_widgets) {
		foreach ($column_widgets as $widget) {
			$current_handlers[] = $widget->handler;
		}
	}
	

	if(!empty($widgets)){
		$title = "<div id='widget_manager_widgets_search'>";
		$title .= "<input title='" . elgg_echo("search") . "' type='text' value='" . elgg_echo("search") . "' onfocus='if($(this).val() == \"" . elgg_echo("search") .  "\"){ $(this).val(\"\"); }' onkeyup='widget_manager_widgets_search($(this).val());'></input>";
		$title .= "</div>";
		$title .= elgg_echo("widget_manager:widgets:lightbox:title:" . $context);
		
		$body = "";
		
		foreach($widgets as $handler => $widget){
			
			$can_add = widget_manager_get_widget_setting($handler, "can_add", $widget_context);
			$allow_multiple = widget_manager_get_widget_setting($handler, "allow_multiple", $widget_context);
			$hide = widget_manager_get_widget_setting($handler, "hide", $widget_context);
			
			if($can_add && !$hide){
				$body .= "<div class='widget_manager_widgets_lightbox_wrapper'>";
				
				if(!$allow_multiple && in_array($handler, $current_handlers)){
					$class = 'elgg-state-unavailable';
				} else {
					$class = 'elgg-state-available';
				} 
				
				if ($allow_multiple) {
					$class .= ' elgg-widget-multiple';
				} else {
					$class .= ' elgg-widget-single';
				}
				
				$body .= "<span class='widget_manager_widgets_lightbox_actions'>";
				$body .= '<li class="' . $class . '" id="elgg-widget-type-'. $handler . '">';
				$body .= "<span>" . elgg_echo('widget:unavailable') . "</span>";
				$body .= elgg_view("input/button", array("class" => "elgg-button-submit", "value" => elgg_echo("widget_manager:button:add")));
				$body .= "</li>";
				$body .= "</span>";
				
				$body .= "<div><b>" . $widget->name . "</b></div>";
				$body .= "<div class='elgg-quiet'>" . $widget->description . "</div>";
				
				$body .= "</div>";
			}
		}
		
		$module_type = "info";
		if(elgg_in_context("admin")){
			$module_type = "inline";
		} 
		
		echo "<div class='elgg-widgets-add-panel hidden'>" . elgg_view_module($module_type, $title, $body, array("id" => "widget_manager_widgets_select")) . "</div>";
	}
