<?php
	global $CONFIG;
	
	$widget = $vars["entity"];
	
	$blog_tags = '<a><p><br><b><i><em><del><pre><strong><ul><ol><li>';
	$feed_url = $widget->rssfeed;
	
	if(!empty($feed_url)){
		if($widget->excerpt == "yes"){
			$excerpt = true;
		} else {
			$exerpt = false;
		}
		
		$num_items = (int) $widget->rss_count;
		if($num_items < 1){
			$num_items = 4;
		}
		
		if($widget->post_date == "yes" || $widget->post_date == "friendly"){
			$post_date = "friendly";
		} elseif($widget->post_date == "date") {
			$post_date = "date";
		} else {
			$post_date = false;
		}
		
		$feed = new SimplePie($feed_url, WIDGETS_RSS_CACHE_LOCATION, WIDGETS_RSS_CACHE_DURATION);
		
		$num_posts_in_feed = $feed->get_item_quantity($num_items);
		
		if(($feed_title = $feed->get_title()) && ($widget->show_feed_title != "no")){
			$title = "<h3><a href='" . $feed->get_permalink() . "' target='_blank'>" .$feed_title . "</a></h3>";
			echo elgg_view("page_elements/contentwrapper", array("body" => $title));
		}
		
		if (empty($num_posts_in_feed)){
			$body = elgg_echo('widgets:rss:error:notfind');
		} else {
			foreach ($feed->get_items(0, $num_posts_in_feed) as $item){
				
				
				 
				if ($excerpt){
					$body .= "<div class='widgets_rss_feed_item'>";
					$body .= "<div><a href='" . $item->get_permalink() . "' target='_blank'>" . $item->get_title() . "</a></div>";
					$body .=  strip_tags($item->get_description(true), $blog_tags);
					if ($post_date == "friendly"){
						$body .= "<div class='widgets_rss_feed_timestamp'>" . friendly_time($item->get_date('U')) . "</div>";
					} elseif ($post_date == "date"){
						$body .= "<div class='widgets_rss_feed_timestamp' title='" . $item->get_date('r') . "'>" . substr($item->get_date('r'),0,16) . "</div>";
					}
					
					$body .= "</div>";	
				} else {
					$body .= "<div>";
					if ($post_date == "friendly"){
						$body .= "<span>" . friendly_time($item->get_date('U')) . "</span> - ";
					} elseif ($post_date == "date"){
						$body .= "<span title='" . $item->get_date('r') . "'>" . substr($item->get_date('r'),0,16) . "</span> - ";
						
					}
					$body .= "<a href='" . $item->get_permalink() . "' target='_blank'>" . $item->get_title() . "</a>";
					$body .= "</div>";
				}
				
				
				
			}
		}
		
		echo elgg_view("page_elements/contentwrapper", array("body" => $body));      
	} else {
		echo elgg_view("page_elements/contentwrapper", array("body" => elgg_echo('widgets:rss:error:notset')));      
	}
?>