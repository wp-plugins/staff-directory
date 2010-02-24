<?php

function get_all_staff($orderby = null, $order = null, $filter = null){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	$staff_directory_categories = $wpdb->prefix . 'staff_directory_categories';
	
	if((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '') AND (isset($filter) AND $filter != '')){
	
		if($orderby == 'name'){
			
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $filter ORDER BY `name` $order");
			
		}
		
		if($orderby == 'category'){
			
			$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories WHERE `cat_id` = $filter ORDER BY name $order");
			
			foreach($categories as $category){
				$cat_id = $category->cat_id;
				//echo $cat_id;
				$staff_by_cat = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $cat_id");
				foreach($staff_by_cat as $staff){
					$all_staff[] = $staff;
				}
			}
		}
		
		return $all_staff;
		
	
	}elseif((isset($orderby) AND $orderby != '') AND (isset($order) AND $order != '')){
		
		if($orderby == 'name'){
			
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " ORDER BY `name` $order");
			
		}
		
		if($orderby == 'category'){
			
			$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " ORDER BY category $order");
			
		}

		
		return $all_staff;
		
	}elseif(isset($filter) AND $filter != ''){
		
		$all_staff = $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE `category` = $filter");
		if(isset($all_staff)){
			return $all_staff;
		}
	
	}else{
	
		return $wpdb->get_results("SELECT * FROM " . STAFF_DIRECTORY_TABLE);
		
	}
}

function get_single_staff_member($id){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	return $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
}

function get_staff_member_name_by_id($id){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	$staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
	return $staff->name;
}

function get_staff_category($cat){
	global $wpdb;
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	return $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE category = '$cat'");
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
			
			if($staff->photo != ''){
				$photo_url = get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo;
				$photo = "<img src=\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\">";
			}else{
				$photo_url = '';
				$photo = '';
			}
			$accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[category]");
			$replace_single_values = array($staff->name, $photo_url, $staff->position, $staff->email_address, $staff->phone_number, $staff->bio, $staff->category);
			
			$accepted_formatted_tags = array("[name_header]", "[photo]", "[email_link]", "[bio_paragraph]");
			$replace_formatted_values = array("<h3>$staff->name</h3>", $photo, "<a href=\"mailto:$staff->email_address\">Email $staff->name</a>", "<p>$staff->bio</p>");
			
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