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


function all_staff(){
	global $wpdb;
		$staff_directory_templates = $wpdb->prefix . 'staff_directory_templates';
		$output = "";
		$all_staff = get_all_staff();
		
		$index_html = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_html'");
		$index_css = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_css'");
		
		$output .= "<style type=\"text/css\">$index_css</style>";
		
		if(preg_match("[staff_loop]", $index_html)){
			$output .= mb_substr($index_html, 0, strpos($index_html, "[staff_loop]"));
		}else{
			$output .= $index_html;
		}
		
		$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));
		
		
		foreach($all_staff as $staff){
			
			$accepted_single_tags = array("[name]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
			$replace_single_values = array($staff->name, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);
			
			$accepted_formatted_tags = array("[name_header]", "[email_link]", "[bio_paragraph]");
			$replace_formatted_values = array("<h3>$staff->name</h3>", "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");
			
			$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
			$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
			
			$output .= $loop_markup;
			$loop_markup = $loop_markup_reset;
		}
		
	$output .= str_replace("[/staff_loop]", "", mb_substr($index_html, strpos($index_html, "[/staff_loop]")));
	echo $output;

}

function all_staff_in_cat($cat){
		global $wpdb;
		$staff_directory_templates = $wpdb->prefix . 'staff_directory_templates';
		$output = "";
		$all_staff = get_all_staff();
		
		$index_html = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_html'");
		$index_css = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_css'");
		
		$output .= "<style type=\"text/css\">$index_css</style>";
		$output .= mb_substr($index_html, 0, strpos($index_html, "[staff_loop]"));
		
		$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));
		
		
		foreach($all_staff as $staff){
			if($staff->category == $cat){
				$accepted_single_tags = array("[name]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
				$replace_single_values = array($staff->name, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);
				
				$accepted_formatted_tags = array("[name_header]", "[email_link]", "[bio_paragraph]");
				$replace_formatted_values = array("<h3>$staff->name</h3>", "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");
				
				$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
				$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
				
				$output .= $loop_markup;
				$loop_markup = $loop_markup_reset;
			}
		}
	
	$output .= str_replace("[/staff_loop]", "", mb_substr($index_html, strpos($index_html, "[/staff_loop]")));
	echo $output;

}

function single_staff_member($id){
		global $wpdb;
		$staff_directory_templates = $wpdb->prefix . 'staff_directory_templates';
		$output = "";
		$all_staff = get_all_staff();
		
		$index_html = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_html'");
		$index_css = $wpdb->get_var("SELECT template_code FROM $staff_directory_templates WHERE template_name = 'staff_index_css'");
		
		$output .= "<style type=\"text/css\">$index_css</style>";
		$output .= mb_substr($index_html, 0, strpos($index_html, "[staff_loop]"));
		
		$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));
		
		
		foreach($all_staff as $staff){
			if($staff->staff_id == $id){
				$accepted_single_tags = array("[name]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
				$replace_single_values = array($staff->name, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);
				
				$accepted_formatted_tags = array("[name_header]", "[email_link]", "[bio_paragraph]");
				$replace_formatted_values = array("<h3>$staff->name</h3>", "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");
				
				$loop_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
				$loop_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $loop_markup);
				
				$output .= $loop_markup;
				$loop_markup = $loop_markup_reset;
			}
		}
	
	$output .= str_replace("[/staff_loop]", "", mb_substr($index_html, strpos($index_html, "[/staff_loop]")));
	echo $output;

}
?>