<?php 

	$plugin = $vars["entity"];

	$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no")
	);
	
	$noyes_options = array_reverse($yesno_options, true);

	$custom_index_options = array(
		"0|0" => elgg_echo('option:no'),
		"1|0" => elgg_echo('widget_manager:settings:custom_index:non_loggedin'),
		"0|1" => elgg_echo('widget_manager:settings:custom_index:loggedin'),
		"1|1" => elgg_echo('widget_manager:settings:custom_index:all')		
	);

	$widget_layout_options = array(
		"33|33|33" => elgg_echo('widget_manager:settings:widget_layout:33|33|33'),
		"50|25|25" => elgg_echo('widget_manager:settings:widget_layout:50|25|25'),
		"25|50|25" => elgg_echo('widget_manager:settings:widget_layout:25|50|25'),
		"25|25|50" => elgg_echo('widget_manager:settings:widget_layout:25|25|50'),	
		"75|25" => elgg_echo('widget_manager:settings:widget_layout:75|25'),		
		"60|40" => elgg_echo('widget_manager:settings:widget_layout:60|40'),		
		"50|50" => elgg_echo('widget_manager:settings:widget_layout:50|50'),		
		"40|60" => elgg_echo('widget_manager:settings:widget_layout:40|60'),		
		"25|75" => elgg_echo('widget_manager:settings:widget_layout:25|75')	
	);
	
	$index_top_row_options = array(
		"none" => elgg_echo('widget_manager:settings:index_top_row:none'),
		"full_row" => elgg_echo('widget_manager:settings:index_top_row:full_row'),
		"two_column_left" => elgg_echo('widget_manager:settings:index_top_row:two_column_left')
	);
?>
<table>
	<tr>
		<td colspan="2">
			<div class='elgg-module-inline'>
				<div class='elgg-head'>
					<h3><?php echo elgg_echo("widget_manager:settings:index"); ?></h3>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:custom_index'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[custom_index]", "value" => $plugin->custom_index, "options_values" => $custom_index_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:widget_layout'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[widget_layout]", "value" => $plugin->widget_layout, "options_values" => $widget_layout_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:index_top_row'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[index_top_row]", "value" => $plugin->index_top_row, "options_values" => $index_top_row_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:disable_free_html_filter'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[disable_free_html_filter]", "value" => $plugin->disable_free_html_filter, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class='elgg-module-inline'>
				<div class='elgg-head'>
					<h3><?php echo elgg_echo("widget_manager:settings:group"); ?></h3>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:enable'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[group_enable]", "value" => $plugin->group_enable, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:option_default_enabled'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[group_option_default_enabled]", "value" => $plugin->group_option_default_enabled, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:option_admin_only'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[group_option_admin_only]", "value" => $plugin->group_option_admin_only, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class='elgg-module-inline'>
				<div class='elgg-head'>
					<h3><?php echo elgg_echo("widget_manager:settings:multi_dashboard"); ?></h3>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:multi_dashboard:enable'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/dropdown", array("name" => "params[multi_dashboard_enabled]", "value" => $plugin->multi_dashboard_enabled, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
</table>
<br />