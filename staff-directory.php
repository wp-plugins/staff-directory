<?php
/*
Plugin Name: Staff Directory
Plugin URI: http://www.89designs.net/2010/01/staff-directory/
Description: Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.
Version: 0.8.04b
Author: Adam Tootle
Author URI: http://www.89designs.net
*/

//error_reporting(E_ALL);


global $wpdb;

$staff_directory_table = $wpdb->prefix . 'staff_directory';

define(STAFF_DIRECTORY_TABLE, $wpdb->prefix . 'staff_directory');
define(STAFF_TEMPLATES, $wpdb->prefix . 'staff_directory_templates');
define(STAFF_PHOTOS_DIRECTORY, WP_CONTENT_DIR . "/uploads/staff-photos/");


require_once( dirname (__FILE__).'/install.php' );
require_once( dirname (__FILE__).'/admin/admin.php' );
require_once( dirname (__FILE__).'/functions.php' );



add_shortcode('staff-directory', 'wp_staff_directory_shortcode_funct');


function wp_staff_directory_shortcode_funct($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'cat' => '',
		'orderby' => '',
		'order' => ''
	), $atts));

	$output = '';
	
	// get all staff
	$param = "id=$id&cat=$cat&orderby=$orderby&order=$order";
	return staff_directory($param);
	
	
}
?>
