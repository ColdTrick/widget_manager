<?php 

	$widget = $vars["entity"];
	
	static $twitter_script_loaded;
	
	$query = $widget->query;
	$title = $widget->tw_title;
	$sub = $widget->tw_subtitle;
	$height = (int) $widget->heigth;
	$background = $widget->background;
	
	if(!empty($title)){
		$title = addslashes($title);
	}
	
	if(!empty($sub)){
		$sub = addslashes($sub);
	}
	
	if(empty($height) || !is_int($height)){
		$height = 300;
	}
	
	if(empty($background)){
		$background = "4690d6";
	}

	//<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
	
	if(!empty($query)){
?>
<div id="twittersearch_<?php echo $widget->getGUID(); ?>"></div>
<script type="text/javascript">
	new TWTR.Widget({
	  version: 2,
	  id: "twittersearch_<?php echo $widget->getGUID(); ?>",
	  type: "search",
	  search: "<?php echo $query; ?>",
	  interval: 10000,
	  title: "<?php echo $title; ?>",
	  subject: "<?php echo $sub; ?>",
	  width: "auto",
	  height: <?php echo $height; ?>,
	  theme: {
	    shell: {
	      background: '#<?php echo $background; ?>',
	      color: '#ffffff'
	    },
	    tweets: {
	      background: '#ffffff',
	      color: '#333333',
	      links: '#4690d6'
	    }
	  },
	  features: {
	    scrollbar: true,
	    loop: false,
	    live: true,
	    hashtags: true,
	    timestamp: true,
	    avatars: true,
	    toptweets: true,
	    behavior: "all"
	  }
	}).render().start();
</script>
<?php 
	} else { 
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo("widgets:twitter_search:not_configured")));
	} 
?>