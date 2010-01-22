<?php
/*
Plugin Name: Staff Directory
Plugin URI: http://www.89designs.net/2009/09/random-product/
Description: Allows Wordpress to keep track of your staff directory for your website. Good for churches, small companies, etc.
Version: 0.6.02b
Author: Adam Tootle
Author URI: http://www.89designs.net
*/

//error_reporting(E_ALL);


global $wpdb;

require_once( dirname (__FILE__).'/install.php' );
require_once( dirname (__FILE__).'/admin/admin.php' );
require_once( dirname (__FILE__).'/database-functions.php' );



function wp_staff_directory_shortcode_funct($atts) {
	extract(shortcode_atts(array(
		'cat' => '',
		'id' => '',
	), $atts));

	$output = '';
	$all_staff = get_all_staff();

	if(!$cat && !$id){
		foreach($all_staff as $staff){
			$output .= "<div style=\"border-bottom:thin solid black; padding:10px 0\">";
			$output .= $staff->name . " - " . $staff->position;
			if($staff->bio){
				$output .= "<p>"  . $staff->bio . "</p>";
			}
			$output .= "<br><a href=\"mailto:" . $staff->email_address . "\">Email " . $staff->name . "</a>";			
			$output .= "</div>";
		}
	}
	
	if($cat && $id){
		return "You can't set both a category and a single id";
	}
	// get all staff in a category
	if($cat){
		foreach($all_staff as $staff){
			if($staff->category == $cat){
				$output .= "<div style=\"border-bottom:thin solid black; padding:10px 0\">";
			$output .= $staff->name . " - " . $staff->position;
			if($staff->bio){
				$output .= "<p>"  . $staff->bio . "</p>";
			}
			$output .= "<br><a href=\"mailto:" . $staff->email_address . "\">Email " . $staff->name . "</a>";			
			$output .= "</div>";
			}
		}
	}
	// get single staff member
	if($id){
		foreach($all_staff as $staff){
			if($staff->staff_id == $id){
				$output .= "<div style=\"padding:10px 0\">";
			$output .= $staff->name . " - " . $staff->position;
			if($staff->bio){
				$output .= "<p>"  . $staff->bio . "</p>";
			}
			$output .= "<br><a href=\"mailto:" . $staff->email_address . "\">Email " . $staff->name . "</a>";			
			$output .= "</div>";
			}
		}
	}
	
	return $output;
}
add_shortcode('staff-directory', 'wp_staff_directory_shortcode_funct');
?>
