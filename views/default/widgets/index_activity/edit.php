<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->activity_count;
	if(empty($count) || !is_int($count)){
		$count = 10;
	}
	
	$contents = array();
	$contents['all'] = 'all';
	if (!empty($vars['config']->registered_entities)) {
		foreach ($vars['config']->registered_entities as $type => $ar) {
			if (count($vars['config']->registered_entities[$type])) {
				foreach ($vars['config']->registered_entities[$type] as $subtype) {
					$keyname = 'item:' . $type . ':' . $subtype;
					$contents[$keyname] = "{$type},{$subtype}";
				}
			} else {
				$keyname = 'item:' . $type;
				$contents[$keyname] = "{$type},";
			}
		}
	}

?>
<div><?php echo elgg_echo("widget_manager:widgets:index_activity:activity_count"); ?></div>
<input type="text" name="params[activity_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />
<div><?php echo elgg_echo("filter"); ?></div>
<select name="params[activity_content]">
<?php
	foreach($contents as $label => $content) {
		if (($widget->activity_content == $content)) {
			$selected = 'selected="selected"';
		} else {
			$selected = '';
		}
		echo "<option value=\"{$content}\" {$selected}>" . elgg_echo($label) . "</option>";
	}
?>
</select>
