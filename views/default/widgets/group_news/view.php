<?php 

	$widget = $vars["entity"];

	$configured_projects = array();

	for($i = 1;$i < 6; $i++){
		$metadata_name = "project_" . $i;
		if($guid = $widget->$metadata_name){
			if($group = get_entity($guid)){
				$configured_projects[$i] = $group;
			} 
		}
	}
	
	$blog_count = (int) $widget->blog_count;
	if($blog_count < 1){
		$blog_count = 5;
	}
	$slide_timeout = (int) $widget->slide_timeout;
	if($slide_timeout < 1){
		$slide_timeout = 10; //seconds
	}
?>

<div class="contentWrapper">

	<?php 
	if(!empty($configured_projects)){
		echo "<div id='widget_group_news_container'>";
		foreach($configured_projects as $key => $group){
			echo "<div id='widget_group_news_" . $group->getGUID() . "'>";
			$icon = elgg_view("groups/icon", array(
							  "entity" => $group,
					    	  "size" => "medium"));
				       
			echo $icon;
			
			echo "<h3>" . $group->name . "</h3>";
			
			$group_news = elgg_get_entities(array("type" => "object", "subtype" => "blog", "container_guid" => $group->getGUID(), "limit" => $blog_count));
			if(!empty($group_news)){
				echo "<ul>";
				foreach($group_news as $news){
					echo "<li><a href='" . $news->getURL() . "'>" . $news->title . "</a></li>";	
				}
			 	echo "</ul>";
			} else {
				echo elgg_echo("widgets:group_news:no_news");
			}
			echo "<div class='clearfloat'></div>";
			echo "</div>";
		}
		
		echo "</div>";
		
		$configured_projects = array_values($configured_projects);
		
		echo "<div id='widget_group_news_navigator'>";
		foreach($configured_projects as $key => $group){
			echo "<span rel='widget_group_news_" . $group->getGUID() . "'>" . ($key + 1). "</span>";
		}
		echo "</div>";
		
		?>
		
		<script type="text/javascript">
			function rotateProjectNews(){
				if($("#widget_group_news_navigator .active").next().length === 0){
					$("#widget_group_news_navigator>span:first").click();
				} else {
					$("#widget_group_news_navigator .active").next().click();
				}
			}
		
			$(document).ready(function(){
				$("#widget_group_news_navigator>span:first").addClass("active");
				var active = $("#widget_group_news_navigator>span:first").attr("rel");
				$("#" + active).show();

				$("#widget_group_news_navigator span").click(function(){
					$("#widget_group_news_navigator span.active").removeClass("active");
					$(this).addClass("active");

					$("#widget_group_news_container>div").hide();
					var active = $(this).attr("rel");
					$("#" + active).show();
				});
				
				setInterval (rotateProjectNews, <?php echo $slide_timeout * 1000;?>);
	
			});
		</script>
		
	<?php 
	} else {
		echo elgg_echo("widgets:group_news:no_projects");
	}
	?>
</div>