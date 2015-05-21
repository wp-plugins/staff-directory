<?php

class StaffDirectoryAdmin {
  static function register_admin_menu_items() {
    add_action('admin_menu', array('StaffDirectoryAdmin', 'add_admin_menu_items'));
  }

  static function add_admin_menu_items() {
    add_submenu_page('edit.php?post_type=staff', 'Staff Directory Settings', 'Settings', 'publish_posts', 'staff-directory-settings', array('StaffDirectoryAdmin', 'settings'));
    add_submenu_page('edit.php?post_type=staff', 'Staff Directory Help', 'Help', 'publish_posts', 'staff-directory-help', array('StaffDirectoryAdmin', 'help'));
    add_submenu_page('edit.php?post_type=staff', 'Staff Directory Import', 'Import Old Staff', 'publish_posts', 'staff-directory-import', array('StaffDirectoryAdmin', 'import'));
  }

  static function settings() {
    if (isset($_POST['staff_templates']['slug']) && $_POST['staff_templates']['slug'] != 'custom') {

      update_option('staff_directory_template_slug', $_POST['staff_templates']['slug']);
      $did_update_options = true;

    } else if ($_POST['staff_templates']['slug'] == 'custom') {

      update_option('staff_directory_template_slug', 'custom');
      if (isset($_POST['staff_templates']['html'])) {
        update_option('staff_directory_html_template', $_POST['staff_templates']['html']);
      }
      if (isset($_POST['staff_templates']['css'])) {
        update_option('staff_directory_css_template', $_POST['staff_templates']['css']);
      }

      $did_update_options = true;

    }

    if (isset($_POST['staff_meta_fields_labels'])) {
      $index = 0;
      $meta_fields_array = array();
      foreach($_POST['staff_meta_fields_labels'] as $meta_label) {
        $slug = strtolower($meta_label);
        $slug = str_replace(' ', '_', $slug);
        if($meta_label != '') {
          $meta_fields_array[] = array(
            'name' => $meta_label,
            'slug' => $slug,
            'type' => $_POST['staff_meta_fields_types'][$index]
          );
        }
        $index++;
      }
      update_option('staff_meta_fields', $meta_fields_array);
    }

    $current_template = get_option('staff_directory_template_slug');

    if($current_template == '' && get_option('staff_directory_html_template') != '') {
      update_option('staff_directory_template_slug', 'custom');
      $current_template = 'custom';
    } else if($current_template == '') {
      update_option('staff_directory_template_slug', 'list');
      $current_template = 'list';
    }

    

    add_thickbox(); // loads thickbox

    require_once(plugin_dir_path(__FILE__) . '../views/admin_settings.php');

  }

  static function help() {
    require_once(plugin_dir_path(__FILE__) . '../views/admin_help.php');
  }

  static function import() {
    $did_import_old_staff = false;
    if (isset($_GET['import']) && $_GET['import'] == 'true') {
      StaffDirectory::import_old_staff();
      $did_import_old_staff = true;
    }
    if (StaffDirectory::has_old_staff_table()):
    ?>

      <h2>Staff Directory Import</h2>
      <p>
        This tool is provided to import staff from an older version of this plugin.
        This will copy old staff members over to the new format, but it is advised
        that you backup your database before proceeding. Chances are you won't need
        it, but it's always better to be safe than sorry! WordPress provides some
        <a href="https://codex.wordpress.org/Backing_Up_Your_Database" target="_blank">instructions</a>
        on how to backup your database.
      </p>

      <p>
        Once you're ready to proceed, simply use the button below to import old
        staff members to the newer version of the plugin.
      </p>

      <p>
        <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-import&import=true" class="button button-primary">Import Old Staff</a>
      </p>

    <?php else: ?>

      <?php if ($did_import_old_staff): ?>

        <div class="updated">
          <p>
            Old staff was successfully imported! You can <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff">view all staff here</a>.
          </p>
        </div>

      <?php else: ?>

        <p>
          It doesn't look like you have any staff members from an older version of the plugin. You're good to go!
        </p>

      <?php endif; ?>

    <?php

    endif;
  }

  static function register_import_old_staff_message() {
    add_action('admin_notices', array('StaffDirectoryAdmin', 'show_import_old_staff_message'));
  }

  static function show_import_old_staff_message() {
    ?>

    <div class="update-nag">
      It looks like you have staff from an older version of the Staff Directory plugin.
      You can <a href="<?php echo get_admin_url(); ?>edit.php?post_type=staff&page=staff-directory-import">import them</a> to the newer version if you would like.
    </div>

    <?php
  }
}
