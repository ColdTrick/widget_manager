<?php
	$entity_stats = get_entity_statistics();
	
	$options_values = array();
	foreach ($entity_stats as $k => $entry) {
		arsort($entry);
		foreach ($entry as $a => $b) {
			$key = $k . "|" . $a;
			
			if ($a == "__base__") {
				$a = elgg_echo("item:{$k}");
				if (empty($a))
					$a = $k;
			} else {
				if (empty($a)) {
					$a = elgg_echo("item:{$k}");
				} else {
					$a = elgg_echo("item:{$k}:{$a}");
				}
	
				if (empty($a)) {
					$a = "$k $a";
				}
			}
			
			$options_values[$key] = $a;
		}
	}
	
	$selected_entities = $vars["entity"]->selected_entities;
	
?>
<div>
	<?php echo elgg_echo("widgets:entity_statistics:settings:selected_entities"); ?><br />
	<?php 
		echo elgg_view("input/hidden", array("name" => "params[selected_entities][]", "value" => "")); // needed to be able to store no selection
		foreach($options_values as $key => $label){
			if(in_array($key, $selected_entities)){
				echo "<input type='checkbox' name='params[selected_entities][]' checked='checked' value='" . $key . "'>" . $label . "<br />";
			} else {
				echo "<input type='checkbox' name='params[selected_entities][]' value='" . $key . "'>" . $label . "<br />";
			}
		}
	?>
</div>