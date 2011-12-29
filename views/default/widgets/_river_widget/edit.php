<?php
/**
 * Edit the widget
 *
 * @package ElggRiverDash
 */

if (!$vars['entity']->content_type) {
	$content_type = 'mine';
} else {
	$content_type = $vars['entity']->content_type;
}

$contents = array();
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
<p>
	<?php echo elgg_echo('river:widget:label:displaynum'); ?>

	<select name="params[num_display]">
		<option value="5" <?php if ($vars['entity']->num_display == 5) echo " selected=\"yes\" "; ?>>5</option>
		<option value="8" <?php if (($vars['entity']->num_display == 8)) echo " selected=\"yes\" "; ?>>8</option>
		<option value="12" <?php if ($vars['entity']->num_display == 12) echo " selected=\"yes\" "; ?>>12</option>
		<option value="15" <?php if ($vars['entity']->num_display == 15) echo " selected=\"yes\" "; ?>>15</option>
	</select>
</p>
<p>	
	<?php echo elgg_echo('river:widget:type'); ?>

	<select name="params[content_type]">
		<option value="mine" <?php if ($content_type == 'mine') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:mine");?></option>
		<option value="friends" <?php if ($content_type != 'mine') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("river:widgets:friends");?></option>
		<option value="all" <?php if ($content_type == 'all') echo " selected=\"yes\" "; ?>><?php echo elgg_echo("all");?></option>
	</select>
</p>

<div><?php echo elgg_echo("filter"); ?></div>
<?php 

$activity_content = $vars["entity"]->getMetadata("activity_content");

echo elgg_view("input/hidden", array("name" => "params[activity_content][]", "value" => "")); // needed to be able to store no selection
foreach($contents as $label => $content){
	if(in_array($content, $activity_content)){
		echo "<input type='checkbox' name='params[activity_content][]' checked='checked' value='" . $content . "'>" . elgg_echo($label) . "<br />";
	} else {
		echo "<input type='checkbox' name='params[activity_content][]' value='" . $content . "'>" . elgg_echo($label) . "<br />";
	}
}