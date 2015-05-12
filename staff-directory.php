<?php
/*
Plugin Name: Staff Directory
Plugin URI: http://www.89designs.net/2010/01/staff-directory/
Description: Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.
Version: 0.9.6
Author: Adam Tootle
Author URI: http://www.adamtootle.com
*/

global $wpdb;
$staff_directory_table = $wpdb->prefix . 'staff_directory';

define('STAFF_DIRECTORY_TABLE', $wpdb->prefix . 'staff_directory');
define('STAFF_TEMPLATES', $wpdb->prefix . 'staff_directory_templates');
define('STAFF_PHOTOS_DIRECTORY', WP_CONTENT_DIR . "/uploads/staff-photos/");

require_once(dirname(__FILE__) . '/classes/staff_directory.php');
require_once(dirname(__FILE__) . '/classes/staff_directory_shortcode.php');
require_once(dirname(__FILE__) . '/classes/staff_directory_admin.php');

StaffDirectory::register_post_types();
StaffDirectoryAdmin::register_admin_menu_items();
StaffDirectoryShortcode::register_shortcode();

if (StaffDirectory::show_import_message()) {
	StaffDirectoryAdmin::register_import_old_staff_message();
}

register_activation_hook( __FILE__, array('StaffDirectory', 'set_default_templates_if_necessary'));

?>
