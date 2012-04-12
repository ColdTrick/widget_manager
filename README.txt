= Features: =
 - Adding widgets from a lightbox 
 - Index widgets (with a few different layouts)
 - Group widgets (instead of group profile modules)
 - Provides a lot of new widgets (see below for the widget list)
 - Forcing of widget (incl positioning) on profile/dashboard (also later!)
 - Option to globally hide a specific widgettype (will even hide widget already placed on profile/dashboard)

Version 4.0 is only for 1.8 and higher
Version 3.9.x will be used for 1.7 patches

IMPORTANT:
Since 4.0:

- Previous default widgets (made with widget manager in Elgg 1.7) configuration is no longer valid!!
- Requires Elgg 1.8.1 or higher!
- Elgg 1.8.2 contains some bugs regarding to reordering widgets
	
= Widgets =	

== General widgets ==

 - Twitter search
 - Content (blog, file, pages) by tag
 - rss
 - tagcloud
 - free html
 - messages
 
== Index widgets ==

 - login box
 - members
 - online members
 - files
 - blogs
 - pages
 - bookmarks
 - groups
 - activity
 - image slider
 - community stats
   
== Group widgets ==

 - activity
 - forum topics
 - files
 - izap_videos
 - tidypics
 - videolist
 - tasks
 
= Info about the fix mechanisme for Default Widgets = 
Fixed widgets always

 - Appear on top of other widgets
 - Can not be removed or edited or dragged by a user
 - Maintain a relation to their default widget, so if the default widgets changes, all user widgets also change!
 - New fixed widgets will also be added to existing users
 
You can fix widgets on a dashboard or profile page. Fixing widgets can be done by clicking on the pin in the widget header. Only default widgets added when widget manager is enabled will have the option to fix. This is because a special attribute will be set on the default widget to keep a relation to the widget when cloning it to the user profile or dashboard. If you do not see the pin, remove the widget and add it again. 

A user can always add his or hers own widgets. They will always be added below the last fixed widget in the destination column.
 
= ToDo: =

== General ==

 - check available widget and saving of widget manage page on a clean install
 - test migrating from 1.7 to 1.8 
 - test different browser (only FF 8 tested)
 
== Multi Dashboard ==
 
 - fluid layout
 - borderless columns
 - colored tabs
 - dashboard background