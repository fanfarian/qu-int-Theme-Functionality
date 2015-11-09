=== Theme Functionality ===
Contributors: Stefan Reichert
Donate link: http://stefan-reichert.com/
Tags: theme functionality, add mime-types, resonsive videos, performance report, admin css, clean header, favicons
Requires at least: 3.6.0
Tested up to: 4.3
Stable tag: 2.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Functionality plugin with important settings, enhancements and fixes for WordPress themes.

== Description ==

= Featured Functions =
1. Allow upload of svg and font files
2. Embed Video iframes responsively
3. Remove admin bar
4. Remove admin navigation items
5. Update notification for Administrators
6. Quick Performance Report
7. Add custom CSS for Administrators
8. Add custom JS for Administrators
9. Remove inline css style from gallery
10. Clean the output of attributes of images in editor
11. Remove width and height in editor
12. Redirect Attachment Pages (mostly images) to their parent page
13. Remove WP generated content from the head
14. Add various favicons and logos for iOS, Android, Windows
15. Removes invalid rel attribute values in the categorylist
16. Add page slug to body class

= 1. Allow upload of svg and font files =
Add mime types for 'svg', 'ttf', 'otf', 'woff', 'woff2', 'eot' to media uploader


= 2. Embed Video iframes responsively = 
Add oEmbedded class for responsive iFrame Videos from Youtube/Vimeo.
You need to add custom css for .embed-container from http://embedresponsively.com/
.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } 
.embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }


= 3. Remove admin bar = 
Removes the Admin bar on front end for users without role 'edit_posts'


= 4. Remove admin navigation items =
Remove 'Comment' navigation link for users without role 'moderate_comments'
Remove 'Tools' navigation link for users without role 'manage_options'


= 5. Update notification for Administrators = 
Show update notification only to admins to prevent user confusion 


= 6. Quick Performance Report =
Display a quick performance report for admins as HTML comment at the bottom of the page


= 7. Add custom CSS for Administrators =
Checks if file exists in (child) 'theme-folder/css/admin.css' and enqueues file automatically


= 8. Add custom JS for Administrators =
Checks if file exists in 'theme-folder/js/admin.min.js' and enqueues file automatically


= 9. Remove inline css style from gallery =
You need to style your gallery through your own css files


= 10. Clean the output of attributes of images in editor = 
Better align classes: alignright. alignleft, aligncenter


= 11. Remove width and height in editor =
Better responsive images
Also sets 'alt' = 'titel' if no alt tag provided for the image
Check if RICG Plugin makes this obsolete


= 12. Redirect Attachment Pages (mostly images) to their parent page  =
Only if parent is available


= 13. Remove WP generated content from the head =
* Category feeds
* Post and comment feeds
* EditURI link
* Windows live writer
* Index link
* Previous link
* Start link
* Links for adjacent posts
* WP version
* Shortlink
* Canonical links
* Remove comment cookie
* Remove WP version from RSS
* Remove WP version from css + scripts
* Remove pesky injected css for recent comments widget
* Clean up comment styles in the head
* Remove emojicons
* WPML information
* WPML CSS
* WPML JavaSript

-> Options page planned in v3.0.0 to decide which content is removed


= 14. Add various favicons and logos = 
Checks if the following file exist in the (child) theme-folder
* touch-icon-192x192.png (192x192)
* apple-touch-icon-180x180-precomposed.png (180x180)
* apple-touch-icon-152x152-precomposed.png (152x152)
* apple-touch-icon-120x120-precomposed.png (120x120)
* apple-touch-icon-76x76-precomposed.png (76x76)
* apple-touch-icon-precomposed.png (57x57)
* favicon.ico (16x16 + 32x32)
* browserconfig.xml 

browserconfig.xml needs the following files
* tile.png (558x558)
* tile-wide.png (558x270)

Also generates tags for application names on mobile
* <meta name="apple-mobile-web-app-title" content="'.get_bloginfo('name').'">
* <meta name="application-name" content="'.get_bloginfo('name').'">'; 


= 15. Removes invalid rel attribute values in the categorylist =


= 16. Add page slug to body class =
Adds the current page slug to the body class


= Website =
https://github.com/fanfarian/sr-theme-functionality


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload 'sr-theme-functionality' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Let the magic happen...

== Frequently Asked Questions ==

= Q. I have a question =
A. Please create an issue on GitHub: https://github.com/fanfarian/sr-theme-functionality/issues


== Screenshots ==
1. No screenshots available

== Changelog ==


= 2.6.0 =
New Features
* Removes invalid rel attribute values in the categorylist
* Add page slug to body class

= 2.5.0 =
Alternative, flexible and better update script for the plugin
* YahnisElsts (https://github.com/YahnisElsts/plugin-update-checker)

= 2.4.0 =
Automatically update the plugin from GitHub with new updater script based on 
* Smashing Magazin (http://www.smashingmagazine.com/2015/08/deploy-wordpress-plugins-with-github-using-transients/)
* YahnisElsts (https://github.com/YahnisElsts/plugin-update-checker)

= 2.3.0 =
* Added script for custom admin javascript in theme-folder/js/admin.min.js inclusion

= 2.2.1 =
* Better Readme

= 2.2.0 =
* New name: sr-theme-functionality
* Removed update script 'GitHub Updater' from 'radishconcepts', better coming in later version

= 2.1.2 =
* GitHub Updater Test

= 2.1 =
* Updates via GitHub Updater from 'radishconcepts'

= 2.0 =
* Custom Posts/Taxonomy in separates Plugin abgespalten

= 1.0 =
* First Version
* Hosted on GitHub
* Plugin Boilerplate template

== Upgrade Notice ==


`<?php code(); // goes in backticks ?>`