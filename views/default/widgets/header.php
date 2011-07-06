<?php
	$widget = $vars["entity"];

	$widgettypes = $vars["widgettypes"];
	$handler = $widget->handler;
	$title = $widgettypes[$widget->handler]->name;
	
	if($widget->widget_manager_custom_title){
		$title = $widget->widget_manager_custom_title;
	}
	
	if (!$title){
		$title = $handler;
	}
	
	if(!empty($widget->widget_manager_custom_url)){
		$widgettypes[$handler]->link = $widget->widget_manager_custom_url;
	}
	
	// determine if a link is configured for the widget title
	if (isset($widgettypes[$handler]->link)) {
		$link = $widgettypes[$handler]->link;
		/* Let's do some basic substitutions to the link */
		$owner = $widget->getOwnerEntity();
		
		/* [USERNAME] */
		$link = preg_replace('#\[USERNAME\]#', $owner->username, $link);
		
		/* [GUID] */
		$link = preg_replace('#\[GUID\]#', $owner->getGUID(), $link);

		/* [BASEURL] */
		$link = preg_replace('#\[BASEURL\]#', $vars['url'], $link);
	}		
	
	if($widget->context == "default_profile" || $widget->context == "default_dashboard"){
		$configuring_defaults = true;
	}
	
?>

<h1>
<?php

	if($configuring_defaults){
		if($widget->fixed){
			$fix_class = " fixed";
		}
		echo "<span class='widget_manager_default_fix" . $fix_class . "' onclick='widget_manager_fix_widget(this, " . $widget->getGUID() . ");'></span>"; 	
	} 
	if (isset($link)) { 
?>
	
	<a href="<?php echo $link; ?>"><?php echo $title; ?></a>
<?php 
	} else { 
		echo $title;
	}
?>	
</h1>
<?php 
	if( ($widget->getOwnerEntity() instanceof ElggUser) || 
		($widget->getOwnerEntity() instanceof ElggSite) || 
		($widget->getOwnerEntity() instanceof ElggGroup)){
		
		if($widget->canEdit()){
			$tools_class = "class='widget_tools_wrapper'";
		}
		
		$tools = "";
		
		if ($configuring_defaults || ($widget->canEdit() && widget_manager_get_widget_setting($handler, "can_remove") && !$widget->fixed)) {
			// if allow the deleting of a widget, display a remove button
			$tools .= "<div onclick='deleteWidget(this);' id='deleter' class='widget_remove_button'></div>"; 
		}
		
		if ($configuring_defaults || ($widget->widget_manager_show_toggle !== "no")) {
			// if fixed 
			$tools .= "<a href='javascript:void(0);' class='toggle_box_contents'>-</a>";
		}
		 
		if ($configuring_defaults || ($widget->canEdit() && ($widget->widget_manager_show_edit !== "no"))) {
			$tools .= "<a href='javascript:void(0);' class='toggle_box_edit_panel'>" . elgg_echo('edit') . "</a>";
		}
		
		if(!empty($tools)){
			echo "<span " . $tools_class . ">";
			echo "<span class='widget_tools'>";
			echo $tools;
			echo "</span>";
			echo "<div class='clearfloat'></div>";
			echo "</span>";
		}
	} 
?>
<div class="clearfloat"></div>