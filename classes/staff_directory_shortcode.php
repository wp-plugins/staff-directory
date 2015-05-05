<?php

class StaffDirectoryShortcode {
  static function register_shortcode() {
    add_shortcode('staff-directory', array('StaffDirectoryShortcode', 'shortcode'));
  }

  static function shortcode($params) {
    extract(shortcode_atts(array(
  		'id' => '',
  		'cat' => '',
  		'orderby' => '',
  		'order' => ''
  	), $params));

  	$output = '';

  	// get all staff
  	$param = "id=$id&cat=$cat&orderby=$orderby&order=$order";
  	return StaffDirectoryShortcode::show_staff_directory($param);
  }

  static function show_staff_directory($param = null){
  	parse_str($param);
  	global $wpdb;
  	$output = '';

  	// make sure we aren't calling both id and cat at the same time
  	if(isset($id) && $id!= '' && isset($cat) && $cat != ''){
  		return "<strong>ERROR: You cannot set both a single ID and a category ID for your Staff Directory</strong>";
  	}

    // go ahead and load all of our template data for processing
  	$index_html = stripslashes(get_option('staff_directory_html_template'));
  	$index_css = stripslashes(get_option('staff_directory_css_template'));

  	$output .= "<style type=\"text/css\">$index_css</style>";
  	$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));
  	// done with our templates, for now

    $query_args = array(
      'post_type' => 'staff'
    );

  	// check if it's a single staff member first, since single members won't be ordered
  	if((isset($id) && $id != '') && (!isset($cat) || $cat == '')){
      $query_args['p'] = $id;
  	}
  	// ends single staff

  	// check if we're returning a staff category
  	if((isset($cat) && $cat != '') && (!isset($id) || $id == '')){
  		$query_args['tax_query'] = array(
        array(
          'taxonomy' => 'staff_category',
          'terms' => array($cat)
        )
      );
  	}

    if(isset($orderby) && $orderby != ''){
      $query_args['orderby'] = $orderby;
    }
    if(isset($order) && $order != ''){
      $query_args['order'] = $order;
    }

    $staff_query = new WP_Query($query_args);
    while($staff_query->have_posts()) {
      $staff_query->the_post();

      $staff_name = get_the_title();
      if (has_post_thumbnail()) {
        $photo_url = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $photo_url[0];
        $photo_tag = get_the_post_thumbnail();
      } else {
        $photo_url = "";
        $photo_tag = "";
      }

      $staff_position = get_post_meta(get_the_ID(), 'position', true);
      $staff_email = get_post_meta(get_the_ID(), 'email', true);
      $staff_phone_number = get_post_meta(get_the_ID(), 'phone_number', true);
      $staff_bio = get_the_content();
      $staff_website = get_post_meta(get_the_ID(), 'website', true);

      $staff_categories = wp_get_post_terms(get_the_ID(), 'staff_category');
      if (count($staff_categories) > 0) {
        $staff_category = $staff_categories[0]->name;
      } else {
        $staff_category = "";
      }

      $accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[website]", "[category]");
  		$replace_single_values = array($staff_name, $photo_url, $staff_position, $staff_email, $staff_phone_number, $staff_bio, $staff_website, $staff_category);

  		$accepted_formatted_tags = array("[name_header]", "[photo]", "[email_link]", "[bio_paragraph]", "[website_link]");
  		$replace_formatted_values = array("<h3>$staff_name</h3>", $photo_tag, "<a href=\"mailto:$staff_email\">Email $staff_name</a>", "<p>$staff_bio</p>", "<a href=\"$staff_website\" target=\"_blank\">View website</a>");

  		$current_staff_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
  		$current_staff_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $current_staff_markup);

  		$output .= $current_staff_markup;
    }

    wp_reset_query();

  	return $output;
  }
}
