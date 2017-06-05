=== WP Login Attempt Log ===
Contributors: fredsted
Donate link: http://simonfredsted.com
Tags: login, security, attempt, log
Requires at least: 3.9
Tested up to: 4.7.5
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

WP Login Attempt Log logs failed login attempts to the WordPress admin site and helps you monitor hacking attempts to your website. Includes search, graphs and more.

Features:

* Bar graphs of attempt counts
* Statistics
* Ignore certain IPs when logging
* Toplist of IPs, usernames, passwords, browsers
* Easily see most used IPs, X amount of recent attempts

Coded by [Simon Fredsted](http://simonfredsted.com). (c) 2014

![screenshot](http://filedump.fredsted.me/Screen%20Shot%202014-06-14%20at%2023.43.25.png)

== Installation ==

To install, simply place files in `[WORDPRESS_ROOT]/wp-content/plugins/wp-login-attempt-log`

Having issues? The file `login-attempt-log.php` should be placed in the path `[WORDPRESS_ROOT]/wp-content/plugins/wp-login-attempt-log/login-attempt-log.php`

== Screenshots ==

1. Displaying the bar graph and statistics. More statistics will show as time goes on.
2. The log viewer interface

== Changelog ==

= 1.3 = 
* The interface now loads faster when there's millions of log entries
* The log viewer now allows searching the logs, with wildcards
* Ability to reset the Login Attempt Log database

= 1.2.2 =
Fix background of Box Stats in Settings area

= 1.2 =
* WordPress 4.0 compatibility

= 1.1 =
* Bar graph layout
* Split CSS

= 1.0 =
* Initial release.
