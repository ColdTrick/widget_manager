<?php 

	$widget = $vars["entity"];
	
	$count = (int) $widget->content_count;
	if(empty($count) || !is_int($count)){
		$count = 8;
	}
	
	$content_type = $widget->content_type;
	
	$content_options_values = array();
	if(is_plugin_enabled("blog")){
		$content_options_values["blog"] = elgg_echo("item:object:blog");
	}
	if(is_plugin_enabled("file")){
		$content_options_values["file"] = elgg_echo("item:object:file");
	}
	if(is_plugin_enabled("pages")){
		$content_options_values["page"] = elgg_echo("item:object:page");
	}
	
	$tags = $widget->tags;
	$tags_option = $widget->tags_option;
	
	if(empty($tags_option)){
		$tags_option = "and";
	}
	
	$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no")
	);

	$tags_options_values = array(
		"and" => elgg_echo("widgets:content_by_tag:tags_option:and"),
		"or" => elgg_echo("widgets:content_by_tag:tags_option:or")
	);
	
	$display_option_options_values = array(
		"normal" => elgg_echo("widgets:content_by_tag:display_option:normal"),
		"slim" => elgg_echo("widgets:content_by_tag:display_option:slim")
	);
	
	if(elgg_view_exists("input/user_autocomplete")){
		echo "<div>". elgg_echo("widgets:content_by_tag:owner_guids") . "</div>";
		
		echo elgg_view("input/user_autocomplete", array("internalname" => "owner_guids", "value" => string_to_tag_array($widget->owner_guids), "include_self" => true));		
		echo elgg_view("input/hidden", array("internalname" => "params[owner_guids]", "value" => $widget->owner_guids));		
	} else {
		if($user = get_loggedin_user()){
			$options_values = array($user->getGUID() => $user->name);
			if($friends = $user->getFriends("", false)){
				foreach($friends as $friend){
					$options_values[$friend->guid] = $friend->name;
				}
			}
			
			if($owner_guid = $widget->owner_guids){
				if(!array_key_exists($owner_guid, $options_values)){
					if($configured_user = get_user($owner_guid)){
						$options_values[$owner_guid] = $configured_user->name; 
					}
				}
			}
			
			natcasesort($options_values);
			$options_values = array(""=> elgg_echo("all")) + $options_values;
			echo "<div>". elgg_echo("widgets:content_by_tag:owner_guids") . "</div>";
			echo elgg_view("input/pulldown", array("internalname" => "params[owner_guids]", "options_values" => $options_values,"value" => $widget->owner_guids));
		}
	}
?>
<div><?php echo elgg_echo("widgets:content_by_tag:content_count"); ?></div>
<input type="text" name="params[content_count]" value="<?php echo elgg_view("output/text", array("value" => $count)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widgets:content_by_tag:entities"); ?></div>
<?php echo elgg_view("input/pulldown", array("internalname" => "params[content_type]", "options_values" => $content_options_values, "value" => $content_type)); ?>

<div><?php echo elgg_echo("widgets:content_by_tag:tags"); ?></div>
<?php echo elgg_view("input/text", array("internalname" => "params[tags]", "value" => $tags)); ?>

<div><?php echo elgg_echo("widgets:content_by_tag:tags_option"); ?></div>
<?php echo elgg_view("input/pulldown", array("internalname" => "params[tags_option]", "options_values" => $tags_options_values, "value" => $tags_option)); ?>
<?php 
	if(elgg_view_exists("input/user_autocomplete")){
		?>
		<script type="text/javascript">
			$("#widgetform<?php echo $widget->getGUID(); ?>").submit(function(){
				var newVal = "";
				$(this).find("input[name='owner_guids[]']").each(function(index, elem){
					newVal += $(elem).val() + ",";
				});
				newVal = newVal.substr(0, (newVal.length - 1));
				$(this).find("input[name='params[owner_guids]']").val(newVal);
			});
		</script>
		<?php 		
	}
?>
<div><?php echo elgg_echo("widgets:content_by_tag:display_option"); ?></div>
<?php echo elgg_view("input/pulldown", array("internalname" => "params[display_option]", "options_values" => $display_option_options_values, "value" => $widget->display_option)); ?>
<div><?php echo elgg_echo("widgets:content_by_tag:highlight_first"); ?></div>
<?php echo elgg_view("input/text", array("internalname" => "params[highlight_first]", "value" => $widget->highlight_first)); ?>