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
    $current_template = get_option('staff_directory_template_slug');
    if($current_template == '' && get_option('staff_directory_html_template') != '') {
      $current_template = 'custom';
    }

  	// make sure we aren't calling both id and cat at the same time
  	if(isset($id) && $id!= '' && isset($cat) && $cat != ''){
  		return "<strong>ERROR: You cannot set both a single ID and a category ID for your Staff Directory</strong>";
  	}

    $query_args = array(
      'post_type' => 'staff',
      'posts_per_page' => -1
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

    switch($current_template){
      case 'list':
      default:
        $output = StaffDirectoryShortcode::html_for_list_template($staff_query);
        break;
      case 'grid':
        $output = StaffDirectoryShortcode::html_for_grid_template($staff_query);
        break;
      case 'custom':
        $output = StaffDirectoryShortcode::html_for_custom_template($staff_query);
        break;

    }

    wp_reset_query();

  	return $output;
  }

  static function html_for_list_template($wp_query) {
    $output = <<<EOT
      <style type="text/css">
        .clearfix {
          clear: both;
        }
        .single-staff {
          margin-bottom: 50px;
        }
        .single-staff .photo {
          float: left;
          margin-right: 15px;
        }
        .single-staff .photo img {
          max-width: 100px;
          height: auto;
        }
        .single-staff .name {
          font-size: 1em;
          line-height: 1em;
          margin-bottom: 4px;
        }
        .single-staff .position {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
        .single-staff .bio {
          margin-bottom: 8px;
        }
        .single-staff .email {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
        .single-staff .phone {
          font-size: .9em;
          line-height: .9em;
        }
        .single-staff .website {
          font-size: .9em;
          line-height: .9em;
        }
      </style>
      <div id="staff-directory-wrapper">
EOT;
    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $name = get_the_title();
      $position = get_post_meta(get_the_ID(), 'position', true);
      $bio = get_the_content();

      if(has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_html = '<div class="photo"><img src="' . $photo_url . '" /></div>';
      } else {
        $photo_html = '';
      }

      if(get_post_meta(get_the_ID(), 'email', true) != '') {
        $email = get_post_meta(get_the_ID(), 'email', true);
        $email_html = '<div class="email">Email: <a href="mailto:' . $email . '">' . $email . '</a></div>';
      } else {
        $email_html = '';
      }

      if(get_post_meta(get_the_ID(), 'phone', true) != '') {
        $phone_html = '<div class="phone">Phone: ' . get_post_meta(get_the_ID(), 'phone', true) . '</div>';
      } else {
        $phone_html = '';
      }

      if(get_post_meta(get_the_ID(), 'website', true) != '') {
        $website = get_post_meta(get_the_ID(), 'website', true);
        $website_html = '<div class="website">Website: <a href="' . $website . '">' . $website . '</a></div>';
      } else {
        $website_html = '';
      }

      $output .= <<<EOT
        <div class="single-staff">
          $photo_html
          <div class="name">$name</div>
          <div class="position">$position</div>
          <div class="bio">$bio</div>
          $email_html
          $phone_html
          $website_html
          <div class="clearfix"></div>
        </div>
EOT;
    }
    $output .= "</div>";
    return $output;
  }

  static function html_for_grid_template($wp_query) {
    $output = <<<EOT
      <style type="text/css">
        .clearfix {
          clear: both;
        }
        .single-staff {
          float: left;
          width: 25%;
          text-align: center;
          padding: 0px 10px;
        }
        .single-staff .photo {
          margin-bottom: 5px;
        }
        .single-staff .photo img {
          max-width: 100px;
          height: auto;
        }
        .single-staff .name {
          font-size: 1em;
          line-height: 1em;
          margin-bottom: 4px;
        }
        .single-staff .position {
          font-size: .9em;
          line-height: .9em;
          margin-bottom: 10px;
        }
      </style>
      <div id="staff-directory-wrapper">
EOT;
    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $name = get_the_title();
      $position = get_post_meta(get_the_ID(), 'position', true);

      if(has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_html = '<div class="photo"><img src="' . $photo_url . '" /></div>';
      } else {
        $photo_html = '';
      }

      $output .= <<<EOT
        <div class="single-staff">
          $photo_html
          <div class="name">$name</div>
          <div class="position">$position</div>
        </div>
EOT;
    }
    $output .= "</div>";
    return $output;
  }

  static function html_for_custom_template($wp_query) {
    $output = '';
    $index_html = stripslashes(get_option('staff_directory_html_template'));
  	$index_css = stripslashes(get_option('staff_directory_css_template'));

  	$output .= "<style type=\"text/css\">$index_css</style>";
  	$loop_markup = $loop_markup_reset = str_replace("[staff_loop]", "", substr($index_html, strpos($index_html, "[staff_loop]"), strpos($index_html, "[/staff_loop]") - strpos($index_html, "[staff_loop]")));

    while($wp_query->have_posts()) {
      $wp_query->the_post();

      $staff_name = get_the_title();
      if (has_post_thumbnail()) {
        $attachment_array = wp_get_attachment_image_src(get_post_thumbnail_id());
        $photo_url = $attachment_array[0];
        $photo_tag = '<img src="' . $photo_url . '" />';
      } else {
        $photo_url = "";
        $photo_tag = "";
      }

      $staff_position = get_post_meta(get_the_ID(), 'position', true);
      $staff_email = get_post_meta(get_the_ID(), 'email', true);
      $staff_email_link = $staff_email != '' ? "<a href=\"mailto:$staff_email\">Email $staff_name</a>" : "";
      $staff_phone_number = get_post_meta(get_the_ID(), 'phone_number', true);
      $staff_bio = get_the_content();
      $staff_website = get_post_meta(get_the_ID(), 'website', true);
      $staff_website_link = $staff_website != '' ? "<a href=\"$staff_website\" target=\"_blank\">View website</a>" : "";

      $staff_categories = wp_get_post_terms(get_the_ID(), 'staff_category');
      if (count($staff_categories) > 0) {
        $staff_category = $staff_categories[0]->name;
      } else {
        $staff_category = "";
      }

      $accepted_single_tags = array("[name]", "[photo_url]", "[position]", "[email]", "[phone]", "[bio]", "[website]", "[category]");
  		$replace_single_values = array($staff_name, $photo_url, $staff_position, $staff_email, $staff_phone_number, $staff_bio, $staff_website, $staff_category);

  		$accepted_formatted_tags = array("[name_header]", "[photo]", "[email_link]", "[bio_paragraph]", "[website_link]");
  		$replace_formatted_values = array("<h3>$staff_name</h3>", $photo_tag, $staff_email_link, "<p>$staff_bio</p>", $staff_website_link);

  		$current_staff_markup = str_replace($accepted_single_tags, $replace_single_values, $loop_markup);
  		$current_staff_markup = str_replace($accepted_formatted_tags, $replace_formatted_values, $current_staff_markup);

  		$output .= $current_staff_markup;
    }
    return $output;
  }
}
