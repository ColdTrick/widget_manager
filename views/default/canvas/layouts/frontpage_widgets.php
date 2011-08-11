<?php

/**
 * Elgg widget layout
 *
 * @package Elgg
 * @subpackage Core
 */

$context = get_context();
$area1widgets = get_widgets($vars["config"]->site_guid, $context, 1);
$area2widgets = get_widgets($vars["config"]->site_guid, $context, 2);
$area3widgets = get_widgets($vars["config"]->site_guid, $context, 3);
$area4widgets = get_widgets($vars["config"]->site_guid, $context, 4);

$isadminloggedin = isadminloggedin();

$layout = get_plugin_setting("widget_layout", "widget_manager");
$full_first_row = false;

switch($layout){
	case "50|25|25":
	case "25|50|25":
	case "25|25|50":
		list($left, $middle, $right) = explode("|", $layout);
		
		$widgets_left_class = "frontpage_widgets_layout_" . $left;
		$widgets_middle_class = "frontpage_widgets_layout_" . $middle;
		$widgets_right_class = "frontpage_widgets_layout_" . $right;
		break;
	case "33|33|33":
		list($left, $middle, $right) = explode("|", $layout);
		
		break;
	case "50|50":
		$full_first_row = true;
		
		$widgets_left_class = "frontpage_widgets_layout_50_50";
		$widgets_middle_class = "frontpage_widgets_layout_0";
		$widgets_right_class = "frontpage_widgets_layout_50_50";
		
		if(!empty($area1widgets) && !empty($area2widgets)){
			$area1widgets = array_merge($area1widgets, $area2widgets);
			$area2widgets = null;
		} elseif(!empty($area2widgets) && empty($area1widgets)){
			$area1widgets = $area2widgets;
			$area2widgets = null;
		}
		break;
	case "75|25":
	case "60|40":
	case "40|60":
	case "25|75":
		$full_first_row = true;
		list($left, $right) = explode("|", $layout);
		
		$widgets_left_class = "frontpage_widgets_layout_" . $left;
		$widgets_middle_class = "frontpage_widgets_layout_0";
		$widgets_right_class = "frontpage_widgets_layout_" . $right;
		
		if(!empty($area1widgets) && !empty($area2widgets)){
			$area1widgets = array_merge($area1widgets, $area2widgets);
			$area2widgets = null;
		} elseif(!empty($area2widgets) && empty($area1widgets)){
			$area1widgets = $area2widgets;
			$area2widgets = null;
		}
		break;
}

$index_top_row = get_plugin_setting("index_top_row", "widget_manager");

if($index_top_row == "full_row" || (in_array($index_top_row, array("two_column_left", "two_column_right")) && $full_first_row)){
	$top_class = "frontpage_widgets_top_full";
} elseif($index_top_row == "two_column_left"){
	$top_class = "frontpage_widgets_top_left frontpage_widgets_top_" . ($left + $middle);
} elseif($index_top_row == "two_column_right"){
	$top_class = "frontpage_widgets_top_right frontpage_widgets_top_" . ($middle + $right);
}

if ($isadminloggedin) {

?>
<form action="<?php echo $vars['url']; ?>action/widgets/reorder" method="post" id="widget_manager_frontpage_reorder_form">
	<textarea name="debugField1" id="debugField1"></textarea>
	<textarea name="debugField2" id="debugField2"></textarea>
	<textarea name="debugField3" id="debugField3"></textarea>
	<textarea name="debugField4" id="debugField4"></textarea>
	
	<input type="hidden" name="context" value="<?php echo $context; ?>" />
	<input type="hidden" name="owner" value="<?php echo $vars["config"]->site_guid; ?>" />
	
	<?php echo elgg_view("input/securitytoken"); ?>
</form>
<?php
		}
?>
<div id="widget_table">

	<?php if(!empty($index_top_row) && ($index_top_row != "none")){ ?>
	<div id="widgets_top" class="<?php echo $top_class; ?>">
		<?php
			if (is_array($area4widgets) && sizeof($area4widgets) > 0){
				foreach($area4widgets as $widget) {
					echo elgg_view_entity($widget);
				}
			}
		?>
	</div>
	<?php } ?>
	
	<?php if($index_top_row == "two_column_right"){ ?>
	<div id="frontpage_widgets_left_column">
		<!-- left widgets -->
		<div id="widgets_left" class="<?php echo $widgets_left_class; ?>">

			<?php
				if (is_array($area1widgets) && sizeof($area1widgets) > 0){
					foreach($area1widgets as $widget) {
						echo elgg_view_entity($widget);
					}
				}
			?>
	
		</div><!-- /#widgets_left -->
	</div>
	<?php } ?>
	
	<?php
		if ($isadminloggedin) {
		?>
			<div id="toggle_customise_edit_panel">
				<!-- customise page button -->
				<a href="javascript:void(0);" class="toggle_customise_edit_panel"><?php echo(elgg_echo('dashboard:configure')); ?></a>
			</div>
		<?php
		}
	?>
	<div id="frontpage_widgets_right_column">
		
		<div id="widgets_right" class="<?php echo $widgets_right_class; ?>">
		
			<?php
	
				if (is_array($area3widgets) && sizeof($area3widgets) > 0){
					foreach($area3widgets as $widget) {
						echo elgg_view_entity($widget);
					}
				}
			?>
		</div><!-- /#widgets_right -->
	</div>
	
	<?php if($index_top_row != "two_column_right"){ ?>
	<div id="frontpage_widgets_left_column">
		<!-- left widgets -->
		<div id="widgets_left" class="<?php echo $widgets_left_class; ?>">

			<?php
				if (is_array($area1widgets) && sizeof($area1widgets) > 0){
					foreach($area1widgets as $widget) {
						echo elgg_view_entity($widget);
					}
				}
			?>
	
		</div><!-- /#widgets_left -->
	</div>
	<?php } ?>
	
	<div id="frontpage_widgets_middle_column">
		<!-- widgets middle -->
		<div id="widgets_middle" class="<?php echo $widgets_middle_class; ?>">

		<?php 
			if (is_array($area2widgets) && sizeof($area2widgets) > 0){
				foreach($area2widgets as $widget) {
					echo elgg_view_entity($widget);
				}
			}
		?>

		</div><!-- /#widgets_middle -->
	</div>
</div>