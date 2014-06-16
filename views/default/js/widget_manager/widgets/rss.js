define(['jquery', 'elgg'], function ($, elgg) {
	
	return function(selector, feed_url) {
		var $wrapper = $(selector);
		$wrapper.empty();
		
		var config = $wrapper.data();
		
		var feed_url = config.feedUrl;
		var limit = config.limit;

		$.getJSON(
			"//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=" + limit + "&output=json_xml&q=" + encodeURIComponent(feed_url) + "&hl=en&callback=?",
			function (data) {
				if (data.responseData) {
					var xmlDoc = $.parseXML(data.responseData.xmlString);
					var feed = data.responseData.feed;
					
					var $items = $(xmlDoc).find("item");
					
					var s = "";
					
					if (config.showFeedTitle) {
						s += "<h3><a href='" + feed.link + "' target='_blank'>" + feed.title + "</a></h3>";
					}
					
					s += "<ul class='widget-manager-rss-result'>";
					$.each(feed.entries, function (index, item) {
						s += "<li class='clearfix'>";
						if (config.showExcerpt) {
							var description = item.content.replace(/(<([^>]+)>)/ig,"");
							
							s += "<div class='pbm'>";
							s += "<div><a href='" + item.link + "' target='_blank'>" + item.title + "</a></div>";
							s += "<div class='elgg-content'>";
							if (config.showItemIcon) {
								var xml_item = $items[index];
								
								var enclosure = $(xml_item).find("enclosure");
								var enclosure_url = enclosure.attr("url");
								var enclosure_type = enclosure.attr("type");
							
								s += "<a href='" + item.link + "' target='_blank'><img class='widgets_rss_feed_item_image' src='" + enclosure_url + "' /></a>";
							}
							
							s += description;
							s += "</div>";
							
							if (config.postDate) {
								var i = new Date(item.publishedDate);
								s += "<div class='elgg-subtext'>" + i.toLocaleDateString() + "</div>";
							}
							
							s += "</div>";
						} else {
							s += "<div>";
							
							if (config.postDate) {
								var i = new Date(item.publishedDate);
								s += i.toLocaleDateString() + " - ";
							}
							
							s += "<a href='" + item.link + "' target='_blank'>" + item.title + "</a>";
							s += "</div>";
						}
						
						s += "</li>";
					});
					s += "</ul>";
					
					$wrapper.append(s);
				} else {
					$wrapper.append(data.responseDetails);
				}
			}
		);
	};
});
