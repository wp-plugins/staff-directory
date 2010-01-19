<?php

function get_single_category_name_by_id($id){
	global $wpdb;
	$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
	$category = $wpdb->get_row("SELECT * FROM $staff_directory_categories_table WHERE cat_id = '$id';");
	return $category->name;
}

?>