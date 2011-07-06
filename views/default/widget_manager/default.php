<?php 
	$context = $vars["context"];
	
	$plugin_fixed_time = get_plugin_setting($context . "_enforce_fixed", "widget_manager");
	$plugin_free_time = get_plugin_setting($context . "_enforce_free", "widget_manager");
	
	$action = $vars["url"] . "action/widget_manager/update_timestamp?context=" . $context;
?>
<div class="widget_manager_default_info">
	<?php echo elgg_echo("widget_manager:defaults:info"); ?>
	<br /><br />
	<div>
		<?php 
			if(!empty($plugin_fixed_time)){
				$date = date("r", $plugin_fixed_time);
			} else {
				$date = elgg_echo("widget_manager:defaults:never");
			}
			
			echo sprintf(elgg_echo("widget_manager:defaults:timestamp"), "<b>fixed</b>", "<b>" . $date . "</b>");?>
		<a href="<?php echo elgg_add_action_tokens_to_url($action . "&type=fixed"); ?>"><?php echo elgg_echo("update"); ?></a>
	</div>
	
	<div>
		<?php
			if(!empty($plugin_free_time)){
				$date = date("r", $plugin_free_time);
			} else {
				$date = elgg_echo("widget_manager:defaults:never");
			}
			echo sprintf(elgg_echo("widget_manager:defaults:timestamp"), "<b>free</b>", "<b>" . $date . "</b>");
		?>
		<a href="<?php echo elgg_add_action_tokens_to_url($action . "&type=free"); ?>"><?php echo elgg_echo("update"); ?></a>
	</div>
</div>