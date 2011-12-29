<?php 
	
	$count = sanitise_int($vars["entity"]->activity_count, false);
	if(empty($count)){
		$count = 10;
	}
	
	/*$contents = array();
	
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
	}*/

?>
<div>
	<?php echo elgg_echo("widget:numbertodisplay"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[activity_count]", "value" => $count, "size" => "4", "maxlength" => "4"));?>
</div>
<?php /*?>
<div>
	<?php echo elgg_echo("filter"); ?><br />
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
	?>
</div>*/