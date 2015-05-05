=== Plugin Name ===
Contributors: adamtootle
Tags: staff directory
Requires at least: 2.3.0
Tested up to: 4.2.1
Stable Tag: tags/0.9.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.

== Description ==

Staff Directory is deigned to keep track of your list of staff members for easy display on your site. It also allows for categorization of your staff.

== Installation ==

1. Upload the `staff-directory` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. See the admin help page for usage instructions

== Changelog ==

= 0.9.4 =
<ul>
<li>Preventing blank links from showing for a staff member's email and website if a value isn't set</li>
</ul>

= 0.9.3 =
<ul>
<li>Fixed a couple of relative path issues in the old staff import tool</li>
</ul>

= 0.9.2 =
<ul>
<li>Fixed an error with the cat shortcode param</li>
</ul>

= 0.9.1 =
<ul>
<li>Added a website field and [website] template tags for staff members</li>
</ul>

= 0.9 =
It's been nearly 5 years to the day since I last updated this. I still get emails
about it so I decided I'd ship an update that uses much more current WordPress
APIs.
<ul>
<li>Rewritten to use WordPress Custom Post Types</li>
<li>Added ability to import old staff members to new management system</li>
<li>Ordering is now much more flexible since it's using the WP_Query ordering param</li>
</ul>

= 0.8.04b =
<ul>
<li>Fixed staff directory tags rendering before content placed above it in a post</li>
<li>Added ordering parameters to shortcodes</li>
<li>Added template tag</li>
</ul>

= 0.8.03b =
<ul>
<li>Fixed mkdir() error</li>
<li>Fixed image issue when displaying a category</li>
</ul>

= 0.8b =

<ul>
<li>Added STAFF PHOTOS!!!</li>
<li>Added multiple deletes</li>
<li>Added filter view on main admin page</li>
<li>Added ordering by name and category on main admin page</li>
</ul>

= 0.7b =
*Added templating system.
*Added the ability to import existing Wordpress users.
*Added a default 'Uncategorized' category for new installs.

= 0.6.02b =
* Enabled editor access to the plugin.

= 0.6.01b =
* Removed a line of debug code.

= 0.6b =
* Initial beta release.
