<?php

function get_all_staff(){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	return $wpdb->get_results("SELECT * FROM $staff_directory_table");
}

function get_single_staff_member($id){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	return $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
}

function get_staff_member_name_by_id($id){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	$staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
	return $staff->name;
}

function get_staff_category($cat){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	return $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE category = '$cat'");
}

?>