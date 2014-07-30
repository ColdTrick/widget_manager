<?php

$widget = $vars["entity"];

$cache_location = elgg_get_config("dataroot") . "widgets/rss";
if (file_exists($cache_location)) {
	mkdir($cache_location, 0755, true);
}

$feed_url = $widget->rssfeed;

$limit = (int) $widget->rss_count;
if ($limit < 1) {
	$limit = 4;
}

$post_date = true;
if ($widget->post_date == "no") {
	$post_date = false;
}

$show_feed_title = false;
if ($widget->show_feed_title == "yes") {
	$show_feed_title = true;
}
$excerpt = false;
if ($widget->excerpt == "yes") {
	$excerpt = true;
}

$show_item_icon = false;
if ($widget->show_item_icon == "yes") {
	$show_item_icon = true;
}

$show_in_lightbox = false;
if ($widget->show_in_lightbox == "yes") {
	elgg_load_js("lightbox");
	elgg_load_css("lightbox");
	
	$show_in_lightbox = true;
}

$show_author = false;
if ($widget->show_author == "yes") {
	$show_author = true;
}

if ($feed_url) {
	
	elgg_load_library("simplepie");
	
	$feed = new SimplePie();
	$feed->set_feed_url($feed_url);
	$feed->set_cache_location($cache_location);
	
	$feed->init();
	
	if ($show_feed_title) {
		echo "<h3>";
		echo elgg_view("output/url", array(
			"text" => $feed->get_title(),
			"href" => $feed->get_permalink(),
			"target" => "_blank"
		));
		echo "</h3>";
	}
	
	echo "<ul class='widget-manager-rss-server-result elgg-list'>";
	
	foreach ($feed->get_items(0, $limit) as $index => $item) {
		echo "<li class='elgg-item'>";
		
		$title = "";
		$title_text = "";
		$content = "";
		$icon = "";
		
		if ($show_item_icon) {
			$enclosures = $item->get_enclosures();
			if (!empty($enclosures)) {
				foreach ($enclosures as $enclosure) {
					if (strpos($enclosure->type, "image/") !== false) {
						$icon .= elgg_view("output/url", array(
							"text" => elgg_view("output/img", array(
								"src" => $enclosure->link,
								"alt" => $item->get_title(),
								"class" => "widgets-rss-server-feed-item-image"
							)),
							"href" => $item->get_permalink(),
							"target" => "_blank"
						));
						break;
					}
				}
			}
		}
		
		$title_text = $item->get_title();
		if ($show_author) {
			$authors = $item->get_authors();
			if (!empty($authors)) {
				$author = $authors[0]->name;
				if (empty($author)) {
					$author = $authors[0]->link;
				}
				if (empty($author)) {
					$author = $authors[0]->email;
				}
				$title_text .= " (" . $author . ")";
			}
		}
		
		if ($show_in_lightbox) {
			$id = "widget-manager-rss-server-" . $widget->getGUID() . "-item-" . $index;
			
			$title = elgg_view("output/url", array(
				"text" => $title_text,
				"href" => "javascript:return void(0);",
				"class" => "elgg-lightbox",
				"data-colorbox-opts" => "{\"inline\": true, \"href\": \"#" . $id . "\", \"innerWidth\": 600}"
			));
			
			$content .= "<div class='hidden'>";
			$content .= elgg_view_module("rss-popup", $item->get_title(), $icon . $item->get_content(), array(
				"id" => $id,
				"class" => "elgg-module-info"
			));
			$content .= "</div>";
		} else {
			$title = elgg_view("output/url", array(
				"text" => $title_text,
				"href" => $item->get_permalink(),
				"target" => "_blank"
			));
		}
		
		if ($excerpt) {
			$content .= "<div class='elgg-content'>";
			$content .= $icon;
			$content .= elgg_view("output/longtext", array("value" => $item->get_description()));
			$content .= "</div>";
		}
		
		if ($post_date) {
			$date_info = getdate($item->get_date("U"));
			
			$date_array = array(
				elgg_echo("date:weekday:" . $date_info["wday"]),
				elgg_echo("date:month:" . str_pad($date_info["mon"], 2, "0", STR_PAD_LEFT), array($date_info["mday"])),
				$date_info["year"]
			);
			
			$content .= "<div class='elgg-subtext'>";
			$content .= implode(" ", $date_array);
			$content .= "</div>";
		}
		
		echo $title;
		echo $content;
		
		echo "</li>";
	}
	
	echo "</ul>";
} else {
	echo elgg_echo("widgets:rss:error:notset");
}