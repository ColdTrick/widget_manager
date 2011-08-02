<?php 

	$english = array(
		
		// special access level
		'LOGGED_OUT' => "Logged out users",
		'access:admin_only' => "Administrators only",
	
		// widget edit wrapper
		'widget_manager:widgets:edit:custom_title' => "Custom title",
		'widget_manager:widgets:edit:custom_url' => "Custom title link",
		'widget_manager:widgets:edit:hide_header' => "Hide header",
		'widget_manager:widgets:edit:disable_widget_content_style' => "No widget style",
		'widget_manager:widgets:edit:show_edit' => "User can edit widget settings", 
		'widget_manager:widgets:edit:show_toggle' => "Widget can be minimized", 
	
		// menu items
		'widget_manager:menu:manage' => "Manage widgets",
		'widget_manager:menu:dashboard' => "Dashboard widgets",
		'widget_manager:menu:profile' => "Profile widgets",
		'widget_manager:menu:index' => "Index widgets",
		
		'widget_manager:groups:enable_widget_manager' => "Enable management of widgets",
	
		// admin settings
		
		'widget_manager:settings:general' => "General",
		'widget_manager:settings:index' => "Index",
		'widget_manager:settings:group' => "Group",
		'widget_manager:settings:other' => "Other",
	
		'widget_manager:settings:lazy_loading_disabled' => "Disable Lazy Loading?",
		'widget_manager:settings:use_widget_lightbox' => "Use a lightbox for adding widgets?",
		'widget_manager:settings:remove_broken_widgets' => "Remove broken widgets?",
		'widget_manager:settings:show_broken_widgets' => "Show broken widgets?",
		'widget_manager:settings:target_column' => "Select the column for new widgets",
		'widget_manager:settings:target_column:left' => "Left",
		'widget_manager:settings:target_column:middle' => "Middle",
		'widget_manager:settings:target_column:right' => "Right",
		'widget_manager:settings:edit_in_lightbox' => "Show the widget edit forms in a lightbox?",
		
		'widget_manager:settings:custom_index' => "Use Widget Manager custom index?",
		'widget_manager:settings:custom_index:non_loggedin' => "For non-loggedin users only",
		'widget_manager:settings:custom_index:loggedin' => "For loggedin users only",
		'widget_manager:settings:custom_index:all' => "For all users",
	
		'widget_manager:settings:widget_layout' => "Choose a widget layout",
		'widget_manager:settings:widget_layout:33|33|33' => "Default layout (33% per column)",
		'widget_manager:settings:widget_layout:50|25|25' => "Wide left column (50%, 25%, 25%)",
		'widget_manager:settings:widget_layout:25|50|25' => "Wide middle column (25%, 50%, 25%)",
		'widget_manager:settings:widget_layout:25|25|50' => "Wide right column (25%, 25%, 50%)",
		'widget_manager:settings:widget_layout:75|25' => "Two column (75%, 25%)",
		'widget_manager:settings:widget_layout:60|40' => "Two column (60%, 40%)",
		'widget_manager:settings:widget_layout:50|50' => "Two column (50%, 50%)",
		'widget_manager:settings:widget_layout:40|60' => "Two column (40%, 60%)",
		'widget_manager:settings:widget_layout:25|75' => "Two column (25%, 75%)",
		
		'widget_manager:settings:index_top_row' => "Show a top row on the index page",
		'widget_manager:settings:index_top_row:none' => "No top row",
		'widget_manager:settings:index_top_row:full_row' => "Full width row",
		'widget_manager:settings:index_top_row:two_column_left' => "Two column aligned left",
		'widget_manager:settings:index_top_row:two_column_right' => "Two column aligned right",
		
		'widget_manager:settings:disable_free_html_filter' => "Disable HTML filtering for Free HTML widgets on index (ADMIN ONLY)",
		
		'widget_manager:settings:group:enable' => "Enable Widget Manager for groups",
		'widget_manager:settings:group:option_default_enabled' => "Widget management for groups default enabled",
		'widget_manager:settings:group:option_admin_only' => "Only administrator can enable group widgets",

		'widget_manager:settings:other:extend_thewire' => "Extend The Wire widget with the option to post update directly from the widget",
	
		// views
		// manage form
		'widget_manager:dashboard:title' => "Dashboard widgets configuration",
		'widget_manager:profile:title' => "Profile widgets configuration",
		'widget_manager:groups:title' => "Groups widgets configuration",
		
		'widget_manager:defaults:info' => "Here you can configure the default widgets. You can also configure the settings of the widgets. If you like to enforce the presence of a widget on the dashboard or the profile, just pin the widget to fix it. Fixed widgets will always be on top of the users widget columns.<br /><br />The configuration (placement and settings) of widgets will be automatically copied to new users. If you like to force the presence of widgets for existing users, you need to press the links below to update the timestamps. These timestamps will be compared to a timestamp of the user. If the users timestamp is older, the widgets will be updated.",
		'widget_manager:defaults:timestamp' => "Update timestamp for %s widgets is: %s",
		'widget_manager:defaults:never' => "Never",
		
		'widget_manager:manage:form:nav:settings' => "Widget settings",
		
		// settings
		'widget_manager:forms:settings:no_widgets' => "No widgets to manage",
		'widget_manager:forms:settings:can_add' => "Can be added",
		'widget_manager:forms:settings:can_remove' => "Can be removed",
		'widget_manager:forms:settings:allow_multiple' => "Multiple",
		'widget_manager:forms:settings:hide' => "Hide",

		// lightbox
		'widget_manager:button:add' => "Add widget",
		'widget_manager:widgets:lightbox:title:dashboard' => "Add widgets to your personal dashboard",
		'widget_manager:widgets:lightbox:title:default_dashboard' => "Add widgets to the DEFAULT dashboard",
		'widget_manager:widgets:lightbox:title:profile' => "Add widgets to your public profile",
		'widget_manager:widgets:lightbox:title:default_profile' => "Add widgets to the DEFAULT profile",
		'widget_manager:widgets:lightbox:title:index' => "Add widgets to the index",
		'widget_manager:widgets:lightbox:title:groups' => "Add widgets to the group profile",
		'widget_manager:widgets:lightbox:not_allowed' => "can't add more",
		'widget_manager_widgets_search' => "Type to search",
		
		// actions
		// manage
		'widget_manager:action:manage:error:context' => "Invalid context to save widget configuration",
		'widget_manager:action:manage:error:save_setting' => "Error while saving the setting %s for widget %s",
		'widget_manager:action:manage:success' => "Widget configuration saved successfully",
		
		// widgets
		'widget_manager:add' => "Click to add a new widget",
		'widget_manager:delete' => "Are you sure you wish to delete this widget?",
		'widget_manager:widgets:edit:advanced' => "Advanced",
			
			// index_login
		'widget_manager:widgets:index_login:name' => "Login",
		'widget_manager:widgets:index_login:description' => "Show a login box",
		'widget_manager:widgets:index_login:welcome' => "<b>%s</b> welcome on the <b>%s</b> community",
		
		// index_members
		'widget_manager:widgets:index_members:name' => "Members",
		'widget_manager:widgets:index_members:description' => "Show the members of your site",
		
		'widget_manager:widgets:index_members:member_count' => "How many members to show",
		
		'widget_manager:widgets:index_members:user_icon' => "Must the users have a profileicon",
		
		'widget_manager:widgets:index_members:no_result' => "No users found",
		
		// index_memebers_online
		'widget_manager:widgets:index_members_online:name' => "Online members",
		'widget_manager:widgets:index_members_online:description' => "Show the online members of your site",
		
		'widget_manager:widgets:index_members_online:member_count' => "How many members to show",
		
		'widget_manager:widgets:index_members_online:user_icon' => "Must the users have a profileicon",
		
		'widget_manager:widgets:index_members_online:no_result' => "No users found",
		
		// index_file
		'widget_manager:widgets:index_file:name' => "Files",
		'widget_manager:widgets:index_file:description' => "Show the latest files on your community",
		
		'widget_manager:widgets:index_file:file_count' => "How many files to show",
		
		'widget_manager:widgets:index_file:no_result' => "No files were found",
		
		// index_blog
		'widget_manager:widgets:index_blog:name' => "Blogs",
		'widget_manager:widgets:index_blog:description' => "Show the latest blogs on your community",
		
		'widget_manager:widgets:index_blog:blog_count' => "How many blogs to show",
		'widget_manager:widgets:index_blog:view_mode' => "How to view the blogs",
		'widget_manager:widgets:index_blog:view_mode:list' => "List",
		'widget_manager:widgets:index_blog:view_mode:preview' => "Preview",
		
		'widget_manager:widgets:index_blog:no_result' => "No blogs were found",
		
		// index_pages
		'widget_manager:widgets:index_pages:name' => "Pages",
		'widget_manager:widgets:index_pages:description' => "Show the latest pages on your community",
		
		'widget_manager:widgets:index_pages:pages_count' => "How many pages to show",
		
		'widget_manager:widgets:index_pages:no_result' => "No pages were found",
		
		// index_thewire
		'widget_manager:widgets:index_thewire:name' => "The Wire",
		'widget_manager:widgets:index_thewire:description' => "Show the latest Wire posts on your community",
		
		'widget_manager:widgets:index_thewire:wire_count' => "How many posts to show",
		
		'widget_manager:widgets:index_thewire:no_result' => "No Wire posts were found",
		
		// index_bookmarks
		'widget_manager:widgets:index_bookmarks:name' => "Bookmarks",
		'widget_manager:widgets:index_bookmarks:description' => "Show the latest bookmarks on your community",
		
		'widget_manager:widgets:index_bookmarks:bookmark_count' => "How many bookmarks to show",
		
		'widget_manager:widgets:index_bookmarks:no_result' => "No bookmarks were found",
		
		// index_groups
		'widget_manager:widgets:index_groups:name' => "Groups",
		'widget_manager:widgets:index_groups:description' => "Show the latest groups on your community",
		
		'widget_manager:widgets:index_groups:group_count' => "How many groups to show",
		'widget_manager:widgets:index_groups:featured' => "Show only featured groups",
		
		'widget_manager:widgets:index_groups:no_result' => "No groups were found",
		
		// index_activity
		'widget_manager:widgets:index_activity:name' => "Activity",
		'widget_manager:widgets:index_activity:description' => "Show the latest activity on your site",
		
		'widget_manager:widgets:index_activity:activity_count' => "How many activity items to show",
		'widget_manager:widgets:index_activity:no_results' => "No activity found",
	
		// image_slider
		'widget_manager:widgets:image_slider:name' => "Image Slider",
		'widget_manager:widgets:image_slider:description' => "Show an Image Slider",
		'widget_manager:widgets:image_slider:seconds_per_slide' => "Seconds per slide",
		'widget_manager:widgets:image_slider:slider_height' => "Height of slides (pixels)",
		'widget_manager:widgets:image_slider:overlay_color' => "Overlay Color (hex)",
		'widget_manager:widgets:image_slider:title' => "Slide",
		'widget_manager:widgets:image_slider:label:url' => "Image url",
		'widget_manager:widgets:image_slider:label:text' => "Text",
		'widget_manager:widgets:image_slider:label:link' => "Link",
		'widget_manager:widgets:image_slider:label:direction' => "Direction",
		
		'widget_manager:widgets:image_slider:direction:top' => "Top",
		'widget_manager:widgets:image_slider:direction:right' => "Right",
		'widget_manager:widgets:image_slider:direction:bottom' => "Bottom",
		'widget_manager:widgets:image_slider:direction:left' => "Left",
	);
	
	add_translation("en", $english);

	$twitter_search = array(
		
		// twitter_search
		'widgets:twitter_search:name' => "Twitter search",
		'widgets:twitter_search:description' => "Display a custom search from Twitter",
		
		'widgets:twitter_search:query' => "Search query",
		'widgets:twitter_search:query:help' => "try some advanced queries",
		'widgets:twitter_search:title' => "Widget title (optional)",
		'widgets:twitter_search:subtitle' => "Widget subtitle (optional)",
		'widgets:twitter_search:height' => "Widget height (pixels)",
		'widgets:twitter_search:background' => "Set a custom background color (HEX eq 4690d6)",
		
		'widgets:twitter_search:not_configured' => "This widget is not yet configured",
		
	);
	add_translation("en", $twitter_search);
	
	$content_by_tag = array(
		
		// content_by_tag
		'widgets:content_by_tag:name' => "Content by tag",
		'widgets:content_by_tag:description' => "Find content by a tag",
		
		'widgets:content_by_tag:owner_guids' => "Who needs to write the items",
		'widgets:content_by_tag:content_count' => "How many items to show",
		'widgets:content_by_tag:entities' => "Which entities to show",
		'widgets:content_by_tag:tags' => "Tag(s) (comma separated)",
		'widgets:content_by_tag:tags_option' => "How to use the tag(s)",
		'widgets:content_by_tag:tags_option:and' => "AND",
		'widgets:content_by_tag:tags_option:or' => "OR",
		'widgets:content_by_tag:display_option' => "How to list the content",
		'widgets:content_by_tag:display_option:normal' => "Normal",
		'widgets:content_by_tag:display_option:slim' => "Slim (single line)",
		'widgets:content_by_tag:highlight_first' => "Number of highlighted items (slim only)",
		
		'widgets:content_by_tag:no_result' => "No results were found",
		
	);
	add_translation("en", $content_by_tag);
	
	$rss = array(
		// RSS widget (based on SimplePie)
		'widgets:rss:title' => "RSS Feed",
		'widgets:rss:description' => "Show a RSS feed (based on SimplePie)",
		'widgets:rss:error:notset' => "No RSS Feed URL provided",
		'widgets:rss:error:notfind' => "No Items found",
		
		'widgets:rss:settings:rss_count' => "Number of feeds to show",
		'widgets:rss:settings:rssfeed' => "URL of the RSS feed",
		'widgets:rss:settings:show_feed_title' => "Show feed title",
		'widgets:rss:settings:excerpt' => "Show an excerpt",
		'widgets:rss:settings:post_date' => "Show post date",
		'widgets:rss:settings:post_date:option:friendly' => "Show friendly time",
		'widgets:rss:settings:post_date:option:date' => "Show date",
		
	);
	add_translation("en", $rss);
	
	$group_files = array(
		// Files widget
		'widgets:group_files:title' => "Group Files",
		'widgets:group_files:description' => "Show the latest group files",
		'widgets:group_files:settings:file_count' => "Number of files to show",
		
	);
	add_translation("en", $group_files);
	
	$group_forum_topics = array(
		// Forum topic widget
		'widgets:group_forum_topics:title' => "Forum Topics",
		'widgets:group_forum_topics:description' => "Show the latest forum topics",
		'widgets:group_forum_topics:settings:topic_count' => "Number of topics to show",
		
	);
	add_translation("en", $group_forum_topics);
	
	$free_html = array(
		// Free HTML
		'widgets:free_html:title' => "Free HTML",
		'widgets:free_html:description' => "Type your own content in HTML",
		
		'widgets:free_html:settings:html_content' => "Please supply the HTML to display",
		'widgets:free_html:settings:use_content_wrapper' => "Use a content wrapper",
		'widgets:free_html:no_content' => "This widget is not yet configured",
		
	);
	add_translation("en", $free_html);
	
	$group_event_calendar = array(
		// Event Calendar widget
		'widgets:group_event_calendar:title' => "Event Calendar",
		'widgets:group_event_calendar:description' => "Show the upcomming events",
		
	);
	add_translation("en", $group_event_calendar);
	
	$group_izap_videos = array(
		// iZap Videos widget
		'widgets:group_izap_videos:title' => "iZap Videos",
		'widgets:group_izap_videos:description' => "Show the latest iZap Videos",
		
	);
	add_translation("en", $group_izap_videos);
	
	$group_tidypics = array(
		// Tidypics widget
		'widgets:group_tidypics:title' => "Tidypics",
		'widgets:group_tidypics:description' => "Show the latest Tidypics album",
	);
	add_translation("en", $group_tidypics);
	
	$group_videolist = array(
		// videolist widget
		'widgets:group_videolist:title' => "Videolist",
		'widgets:group_videolist:description' => "Show the latest video's from this group",
		
	);
	add_translation("en", $group_videolist);
	
	$thewire_post = array(
		// thewire_post widget
		'widgets:thewire_post:title' => "Update the Wire",
		'widgets:thewire_post:description' => "Update your status on the wire from this widget",
		
	);
	add_translation("en", $thewire_post);
	
	$tagcloud = array(
		// thewire_post widget
		'widgets:tagcloud:title' => "Tagcloud",
		'widgets:tagcloud:description' => "Shows a tagcloud based on all the content on the site, in the group or from the user",
		'widgets:tagcloud:no_data' => "No data available to display a tagcloud",
	);
	add_translation("en", $tagcloud);
	
	$group_news = array(
		// group_news widget
		"widgets:group_news:title" => "Group News", 
		"widgets:group_news:description" => "Shows latest 5 blogs from various groups", 
		"widgets:group_news:no_projects" => "No groups configured", 
		"widgets:group_news:no_news" => "No blogs for this group", 
		"widgets:group_news:settings:project" => "Group", 
		"widgets:group_news:settings:no_project" => "Select a group" 
	);
	add_translation("en", $group_news);
	
?>