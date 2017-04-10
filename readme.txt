=== If-Modified-Since ===
Contributors: ezraverheijen
Donate link: http://bit.ly/1eC8iDE
Tags: if modified since, If Modified Since, If-Modified-Since, seo, SEO, search engine optimization, google, bing, yahoo
Requires at least: 3.5
Tested up to: 4.7.3
Stable tag: 1.0
License: GPL v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Comply with Webmaster Guidelines, save some bandwith and reduce server load with the If-Modified-Since header.

== Description ==

If-Modified-Since tells search engine spiders one of two things about a webpage:

* This webpage has not changed, no need to download again.
* This webpage has changed so download again because there is new information.

If the requested page has not been modified since the time specified by a search engine spider, a 304 (not modified) response will be returned without any message-body.

If you have any issues using If-Modified-Since, find a bug or have an idea to make the plugin even better then please [help to improve If-Modified-Since](https://github.com/ezraverheijen/wp-if-modified-since).
If you don’t report it, I can’t fix it!

== Installation ==

1. Upload the `if-modified-since` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. No need to configure, it just works!

== Frequently Asked Questions ==

= Do I need this when I already use a caching plugin like W3 Total Cache? =

Some plugins like W3TC support the If-Modified-Since HTTP header, so there's no need to install this plugin.

= How do I know if the plugin works after installing? =

You can test If-Modified-Since [here](https://www.hscripts.com/tools/if-modified-since/)

== Screenshots ==

== Changelog ==

= 1.0 =
* Stable release

* Enhancements:
	* Removed PHP < 5.4 legacy code
	* Added prevention for class name collision
	* Modified the plugin to only output a Last-Modified header if the response is not 304

= 0.9 =
* Beta release
