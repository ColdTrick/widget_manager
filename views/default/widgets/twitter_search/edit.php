<?php 

	$widget = $vars["entity"];
	
	$query = $widget->query;
	$title = $widget->tw_title;
	$sub = $widget->tw_subtitle;
	$height = (int) $widget->heigth;
	$background = $widget->background;
	
	if(empty($height) || !is_int($height)){
		$height = 300;
	}
	
	if(empty($background)){
		$background = "4690d6";
	}

?>
<div><?php echo elgg_echo("widgets:twitter_search:query"); ?></div>
<?php echo elgg_view("input/text", array("internalname" => "params[query]", "value" => $query)); ?>
<div style="text-align:right"><a href="http://search.twitter.com/operators" target="_blank"><?php echo elgg_echo("widgets:twitter_search:query:help"); ?></a></div>

<div><?php echo elgg_echo("widgets:twitter_search:title"); ?></div>
<?php echo elgg_view("input/text", array("internalname" => "params[tw_title]", "value" => $title)); ?>

<div><?php echo elgg_echo("widgets:twitter_search:subtitle"); ?></div>
<?php echo elgg_view("input/text", array("internalname" => "params[tw_subtitle]", "value" => $sub)); ?>

<div><?php echo elgg_echo("widgets:twitter_search:height"); ?></div>
<input type="text" name="params[height]" value="<?php echo elgg_view("output/text", array("value" => $height)); ?>" size="4" maxlength="4" />

<div><?php echo elgg_echo("widgets:twitter_search:background"); ?></div>
<input type="text" name="params[background]" value="<?php echo elgg_view("output/text", array("value" => $background)); ?>" size="6" maxlength="6" />