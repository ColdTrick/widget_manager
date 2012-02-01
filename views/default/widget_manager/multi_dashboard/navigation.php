<?php

$max_tab_title_length = 10;

$md_entities = elgg_extract("entities", $vars);
$selected_guid = get_input("multi_dashboard_guid");

if($md_entities){
	
	$tabs = array();
	foreach($md_entities as $key => $entity){
		
		$selected = false;
		if($entity->getGUID() == get_input("multi_dashboard_guid")){
			$selected = true;
		} elseif($key == 0 && empty($selected_guid)){
			$selected = true;
		}
		
		$tab_title = $entity->title;
		if(strlen($tab_title) > $max_tab_title_length){
			$tab_title = substr($tab_title, 0, $max_tab_title_length);
		}
		
		$tabs[] = array(
				"text" => $tab_title . elgg_view_icon("settings-alt", "widget-manager-multi-dashboard-tabs-edit"),
				"href" => $entity->getURL(),
				"title" => $entity->title,
				"id" => $entity->getContext(),
				"selected" => $selected,
				"rel" => $entity->getGUID()
			);
	}
	
	echo elgg_view("navigation/tabs", array("id" => "widget-manager-multi-dashboard-tabs", "tabs" => $tabs));
}

?>
<script type="text/javascript">

	$(document).ready(function(){
		$(".widget-manager-multi-dashboard-tabs-edit").click(function(event){
			var id = $(this).parent().attr("rel");
			$.fancybox({
				"href" : "<?php echo elgg_get_site_url(); ?>multi_dashboard/edit/" + id,
				"autoDimensions" : false,
				"width": 400,
				"height": 350
				});
			event.preventDefault();
		});
	});
</script>