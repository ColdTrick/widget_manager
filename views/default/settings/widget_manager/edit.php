<?php 

	$plugin = $vars["entity"];

	$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no")
	);
	
	$noyes_options = array(
		"no" => elgg_echo("option:no"),
		"yes" => elgg_echo("option:yes")
	);


?>
<table>
	<tr>
		<td colspan="2">
			<h3 class="settings"><?php echo elgg_echo('widget_manager:settings:general'); ?></h3>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:lazy_loading_disabled'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[lazy_loading_disabled]", "value" => $plugin->lazy_loading_disabled, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:remove_broken_widgets'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[remove_broken_widgets]", "value" => $plugin->remove_broken_widgets, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:show_broken_widgets'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[show_broken_widgets]", "value" => $plugin->show_broken_widgets, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:target_column'); ?>
		</td>
		<td>
			<select name="params[target_column]">
				<option value="widgets_left" <?php if ($vars['entity']->target_column == 'widgets_left') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('widget_manager:settings:target_column:left'); ?></option>
				<option value="widgets_middle" <?php if ($vars['entity']->target_column == 'widgets_middle') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('widget_manager:settings:target_column:middle'); ?></option>
				<option value="widgets_right" <?php if ($vars['entity']->target_column != 'widgets_left' && $vars['entity']->target_column != 'widgets_middle') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('widget_manager:settings:target_column:right'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3 class="settings"><?php echo elgg_echo('widget_manager:settings:index'); ?></h3>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:custom_index'); ?>
		</td>
		<td>
			<select name="params[custom_index]">
				<option value="0|0" <?php if ($vars['entity']->custom_index == '0|0') echo " selected='yes' "; ?>><?php echo elgg_echo('option:no'); ?></option>
				<option value="1|0" <?php if ($vars['entity']->custom_index == '1|0') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:custom_index:non_loggedin'); ?></option>
				<option value="0|1" <?php if ($vars['entity']->custom_index == '0|1') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:custom_index:loggedin'); ?></option>
				<option value="1|1" <?php if ($vars['entity']->custom_index == '1|1') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:custom_index:all'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:widget_layout'); ?>
		</td>
		<td>
			<select name="params[widget_layout]">
				<option value="33|33|33" <?php if ($vars['entity']->widget_layout == '33|33|33') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:33|33|33'); ?></option>
				<option value="50|25|25" <?php if ($vars['entity']->widget_layout == '50|25|25') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:50|25|25'); ?></option>
				<option value="25|50|25" <?php if ($vars['entity']->widget_layout == '25|50|25') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:25|50|25'); ?></option>
				<option value="25|25|50" <?php if ($vars['entity']->widget_layout == '25|25|50') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:25|25|50'); ?></option>
				<option value="75|25" <?php if ($vars['entity']->widget_layout == '75|25') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:75|25'); ?></option>
				<option value="60|40" <?php if ($vars['entity']->widget_layout == '60|40') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:60|40'); ?></option>
				<option value="50|50" <?php if ($vars['entity']->widget_layout == '50|50') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:50|50'); ?></option>
				<option value="40|60" <?php if ($vars['entity']->widget_layout == '40|60') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:40|60'); ?></option>
				<option value="25|75" <?php if ($vars['entity']->widget_layout == '25|75') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:widget_layout:25|75'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:index_top_row'); ?>
		</td>
		<td>
			<select name="params[index_top_row]">
				<option value="none" <?php if ($vars['entity']->index_top_row == 'none') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:index_top_row:none'); ?></option>
				<option value="full_row" <?php if ($vars['entity']->index_top_row == 'full_row') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:index_top_row:full_row'); ?></option>
				<option value="two_column_left" <?php if ($vars['entity']->index_top_row == 'two_column_left') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:index_top_row:two_column_left'); ?></option>
				<option value="two_column_right" <?php if ($vars['entity']->index_top_row == 'two_column_right') echo " selected='yes' "; ?>><?php echo elgg_echo('widget_manager:settings:index_top_row:two_column_right'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:disable_free_html_filter'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[disable_free_html_filter]", "value" => $plugin->disable_free_html_filter, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
			<h3 class="settings"><?php echo elgg_echo('widget_manager:settings:group'); ?></h3>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:enable'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[group_enable]", "value" => $plugin->group_enable, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:option_default_enabled'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[group_option_default_enabled]", "value" => $plugin->group_option_default_enabled, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo elgg_echo('widget_manager:settings:group:option_admin_only'); ?>
		</td>
		<td>
			<?php echo elgg_view("input/pulldown", array("internalname" => "params[group_option_admin_only]", "value" => $plugin->group_option_admin_only, "options_values" => $noyes_options)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3 class="settings"><?php echo elgg_echo('widget_manager:settings:other'); ?></h3>
		</td>
	</tr>
</table>