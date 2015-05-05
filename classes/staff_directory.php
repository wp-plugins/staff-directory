<?php

class StaffDirectory {

  #
  # Init custom post types
  #

  static function register_post_types() {
    add_action('init', array('StaffDirectory', 'create_post_types'));
    add_action('init', array('StaffDirectory', 'create_staff_taxonomies'));
    add_filter('enter_title_here', array('StaffDirectory', 'staff_title_text'));
    add_filter('admin_head', array('StaffDirectory', 'remove_media_buttons'));
    add_action('add_meta_boxes_staff', array('StaffDirectory', 'add_staff_custom_meta_boxes'));
    add_action('save_post', array('StaffDirectory', 'save_meta_boxes'));
  }

  static function create_post_types() {
    register_post_type( 'staff',
      array(
        'labels' => array(
          'name' => __( 'Staff' )
        ),
        'supports' => array(
          'title',
          'editor',
          'thumbnail'
        ),
        'public' => true,
        'taxonomies' => array('staff_category')
      )
    );
  }

  static function create_staff_taxonomies() {
    register_taxonomy('staff_category', 'staff', array(
  		'hierarchical' => true,
  		'labels' => array(
  			'name' => _x( 'Staff Category', 'taxonomy general name' ),
  			'singular_name' => _x( 'staff-category', 'taxonomy singular name' ),
  			'search_items' =>  __( 'Search Staff Categories' ),
  			'all_items' => __( 'All Staff Categories' ),
  			'parent_item' => __( 'Parent Staff Category' ),
  			'parent_item_colon' => __( 'Parent Staff Category:' ),
  			'edit_item' => __( 'Edit Staff Category' ),
  			'update_item' => __( 'Update Staff Category' ),
  			'add_new_item' => __( 'Add New Staff Category' ),
  			'new_item_name' => __( 'New Staff Category Name' ),
  			'menu_name' => __( 'Staff Categories' ),
  		),
  		'rewrite' => array(
  			'slug' => 'staff-categories',
  			'with_front' => false,
  			'hierarchical' => true
  		),
  	));
  }

  #
  # Custom post type customizations
  #

  static function staff_title_text( $title ){
    $screen = get_current_screen();
    if ($screen->post_type == 'staff') {
      $title = "Enter staff member's name";
    }

    return $title;
  }

  static function remove_media_buttons() {
    $screen = get_current_screen();
		if($screen->post_type == 'staff') {
		    remove_action('media_buttons', 'media_buttons');
    }
	}

  static function add_staff_custom_meta_boxes() {
    add_meta_box( 'staff-meta-box', __('Staff Details'), array('StaffDirectory', 'staff_meta_box_output'), 'staff', 'normal', 'high' );
  }

  static function staff_meta_box_output( $post ) {

    wp_nonce_field('staff_meta_box_nonce_action', 'staff_meta_box_nonce');

    ?>

    <p>
      <label for="staff[position]"><?php _e( 'Position'); ?>:</label>
      <input type="text" name="staff[position]" value="<?php echo get_post_meta($post->ID, 'position', true); ?>" />
    </p>

    <p>
      <label for="staff[email]"><?php _e( 'Email'); ?>:</label>
      <input type="text" name="staff[email]" value="<?php echo get_post_meta($post->ID, 'email', true); ?>" />
    </p>

    <p>
      <label for="staff[phone_number]"><?php _e( 'Phone Number'); ?>:</label>
      <input type="text" name="staff[phone_number]" value="<?php echo get_post_meta($post->ID, 'phone_number', true); ?>" />
    </p>

    <?php
  }

  static function save_meta_boxes($post_id) {
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return;

    if(!isset( $_POST['staff_meta_box_nonce'] ) || !wp_verify_nonce($_POST['staff_meta_box_nonce'], 'staff_meta_box_nonce_action'))
      return;

    if(!current_user_can('edit_post', get_the_id()))
      return;

    if(isset($_POST['staff']['position']))
      update_post_meta($post_id, 'position', esc_attr($_POST['staff']['position']));

    if(isset($_POST['staff']['email']))
      update_post_meta($post_id, 'email', esc_attr($_POST['staff']['email']));

    if(isset($_POST['staff']['phone_number']))
      update_post_meta($post_id, 'phone_number', esc_attr($_POST['staff']['phone_number']));
  }

  #
  # Default templates
  #

  static function set_default_templates_if_necessary() {
    if (get_option('staff_directory_html_template', true) == '') {
      $default_html_template = "<div class=\"staff-directory\">

        [staff_loop]

            [name_header]
            [position]
            [email_link]
            [bio_paragraph]

            <div class=\"staff-directory-divider\">
            </div>

        [/staff_loop]

        </div>";
        update_option('staff_directory_html_template', $default_html_template);
    }

    if (get_option('staff_directory_css_template', true) == '') {
      $default_css_template = ".staff-directory-divider{
            border-top: solid black thin;
            width: 90%;
            margin:15px 0;
        }";
        update_option('staff_directory_css_template', $default_css_template);
    }
  }

  #
  # Related to old staff members
  #

  static function has_old_staff_table() {
    global $wpdb;
    $staff_directory_table = $wpdb->prefix . 'staff_directory';

    $old_staff_sql = "SHOW TABLES LIKE '$staff_directory_table'";
    $old_staff_table_results = $wpdb->get_results($old_staff_sql);

    return count($old_staff_table_results) > 0;
  }

  static function show_import_message() {
    if (
      isset($_GET['page'])
      &&
      $_GET['page'] == 'staff-directory-import'
      &&
      isset($_GET['import'])
      &&
      $_GET['import'] == 'true'
    )
      return false;

    return StaffDirectory::has_old_staff_table();
  }

  static function get_old_staff($orderby = null, $order = null, $filter = null){
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

  static function import_old_staff() {
    global $wpdb;

    $old_categories_table = $wpdb->prefix . 'staff_directory_categories';
    $old_staff_directory_table = $wpdb->prefix . 'staff_directory';
    $old_templates_table = STAFF_TEMPLATES;

    #
    # Copy old categories over first
    #

    $old_staff_categories_sql = "
      SELECT
        cat_id, name

      FROM
        $old_categories_table
    ";

    $old_staff_categories = $wpdb->get_results($old_staff_categories_sql);

    foreach($old_staff_categories as $category) {
      wp_insert_term($category->name, 'staff_category');
    }

    #
    # Now copy old staff members over
    #

    $old_staff = StaffDirectory::get_old_staff();
    foreach ($old_staff as $staff) {
      $new_staff_array = array(
        'post_title'  => $staff->name,
        'post_content'  => $staff->bio,
        'post_type' => 'staff',
        'post_status' => 'publish'
      );
      $new_staff_post_id = wp_insert_post($new_staff_array);
      update_post_meta($new_staff_post_id, 'position', $staff->position);
      update_post_meta($new_staff_post_id, 'email', $staff->email_address);
      update_post_meta($new_staff_post_id, 'phone_number', $staff->phone_number);

      if (isset($staff->category)) {
        $old_category_sql = "
          SELECT
            cat_id, name

          FROM
            $old_categories_table

          WHERE
            cat_id=$staff->category
        ";
        $old_category = $wpdb->get_results($old_category_sql);
        $new_category = get_term_by('name', $old_category[0]->name, 'staff_category');
        wp_set_post_terms($new_staff_post_id, array($new_category->term_id), 'staff_category');
      }

      if (isset($staff->photo) && $staff->photo != '') {
        $upload_dir = wp_upload_dir();
        $upload_dir = $upload_dir['basedir'];
        $image_path = $upload_dir . '/staff-photos/' . $staff->photo;
        $filetype = wp_check_filetype($image_path);
        $attachment_id = wp_insert_attachment(array(
          'post_title' => $staff->photo,
          'post_content' => '',
          'post_status' => 'publish',
          'post_mime_type' => $filetype['type']
        ), $image_path, $new_staff_post_id);
        set_post_thumbnail($new_staff_post_id, $attachment_id);
      }
    }

    #
    # Now copy templates over
    #

    $old_html_template_sql = "
      SELECT
        template_code

      FROM
        $old_templates_table

      WHERE
        template_name='staff_index_html'
    ";
    $old_html_template_results = $wpdb->get_results($old_html_template_sql);
    update_option('staff_directory_html_template', $old_html_template_results[0]->template_code);

    $old_css_template_sql = "
      SELECT
        template_code

      FROM
        $old_templates_table

      WHERE
        template_name='staff_index_css'
    ";
    $old_css_template_results = $wpdb->get_results($old_css_template_sql);
    update_option('staff_directory_css_template', $old_css_template_results[0]->template_code);

    #
    # Now delete the old tables
    #

    $drop_tables_sql = "
      DROP TABLE
        $old_categories_table, $old_staff_directory_table, $old_templates_table
    ";
    $wpdb->get_results($drop_tables_sql);
  }
}