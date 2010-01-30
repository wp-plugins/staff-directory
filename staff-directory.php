<?php
/*
Plugin Name: Staff Directory
Plugin URI: http://www.89designs.net/2009/09/random-product/
Description: Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.
Version: 0.7b
Author: Adam Tootle
Author URI: http://www.89designs.net
*/

//error_reporting(E_ALL);


global $wpdb;

require_once( dirname (__FILE__).'/install.php' );
require_once( dirname (__FILE__).'/admin/admin.php' );
require_once( dirname (__FILE__).'/functions.php' );

add_shortcode('staff-directory', 'wp_staff_directory_shortcode_funct');


function wp_staff_directory_shortcode_funct($atts) {
	extract(shortcode_atts(array(
		'cat' => '',
		'id' => '',
	), $atts));

	$output = '';
	
	// get all staff
	if(!$cat && !$id){
		all_staff();
	}
	
	if($cat && $id){
		return "You can't set both a category and a single id";
	}
	// get all staff in a category
	if($cat){
		all_staff_in_cat($cat);
	}
	// get single staff member
	if($id){
		single_staff_member($id);
	}
	
}
?>
