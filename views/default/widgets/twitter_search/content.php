<?php 

	$widget = $vars["entity"];
	
	$height = sanitise_int($widget->height, false);
	
	$widget_id = $widget->widget_id;
	
	if (!empty($widget_id)) {
		?>
		<a class="twitter-timeline" data-dnt="true" data-widget-id="<?php echo $widget_id; ?>" <?php if ($height) { echo "height='" . $height . "'"; } ?>></a>
		<script>
			if(typeof twttr !== 'undefined'){
				twttr.widgets.load();
			}
			
			!function(d,s,id){
				var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
				if(!d.getElementById(id)){
					js=d.createElement(s);
					js.id=id;
					js.src=p+"://platform.twitter.com/widgets.js";
					fjs.parentNode.insertBefore(js,fjs);
				}
			}(document,"script","twitter-wjs");
		</script>
		
<?php 
	} else { 
		echo elgg_echo("widgets:twitter_search:not_configured");
	} 
