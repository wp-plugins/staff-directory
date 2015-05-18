=== Plugin Name ===
Contributors: adamtootle
Tags: staff directory
Requires at least: 2.3.0
Tested up to: 4.2.1
Stable Tag: tags/0.9.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 Easily display a list of staff/faculty/team members. Supports staff categories as well. Good for churches, companies, teams, etc.

== Description ==

Staff Directory is deigned to help you easily display a list of staff/faculty/team members. It also supports staff categories as well as an array of options for the [staff-directory] shortcode.

The plugin provides 2 basic templates but also offers a completely custom template option.

== Installation ==

1. Upload the `staff-directory` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. See the admin help page for usage instructions

== Changelog ==

= 0.9.7 =
<ul>
<li>Added an ID column to the staff categories table for easily getting the new IDs</li>
</ul>

= 0.9.6 =
<ul>
<li>Fixed a potential error with how the array of featured imgage data is accessed</li>
<li>Fixed how the template defaults to 'custom' on the frontend if the template option isn't set but the custom html option is</li>
</ul>

= 0.9.5 =
<ul>
<li>Added a couple of stock templates for anyone who may not want to use the custom template option</li>
<li>Fixed the issue where staff queries were limited to 10 results</li>
<li>Changed how the [photo] template tag was being replaced</li>
</ul>

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
<ul>
<li>Added templating system.</li>
<li>Added the ability to import existing Wordpress users.</li>
<li>Added a default 'Uncategorized' category for new installs.</li>
</ul>

= 0.6.02b =
<ul>
<li>Enabled editor access to the plugin.</li>
</ul>

= 0.6.01b =
<ul>
<li>Removed a line of debug code.</li>
</ul>

= 0.6b =
<ul>
<li>Initial beta release.</li>
</ul>
