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

    $current_template = get_option('staff_directory_template_slug');

    if($current_template == '' && get_option('staff_directory_html_template') != '') {
      update_option('staff_directory_template_slug', 'custom');
      $current_template = 'custom';
    } else if($current_template == '') {
      update_option('staff_directory_template_slug', 'list');
      $current_template = 'list';
    }

    add_thickbox(); // loads thickbox
    ?>

    <script type="text/javascript">
      jQuery(document).ready(function($){
        $('input[name="staff_templates[slug]"]').on('change', function(){
          if($(this).val() == 'custom') {
            $("#custom-template").slideDown();
          } else {
            $("#custom-template").slideUp();
          }
        })
      });
    </script>

    <style type="text/css">
      div.updated.staff-success-message {
        margin-left: 0px;
        margin-top: 20px;
      }
    </style>

    <?php if($did_update_options): ?>
      <div id="message" class="updated notice notice-success is-dismissible below-h2 staff-success-message">
        <p>Options updated.</p>
      </div>
    <?php endif; ?>

    <form method="post">

      <h2>Templates</h2>

      <p>Choose template:</p>

      <p>
        <?php if($current_template == 'list'): ?>
          <input type="radio" name="staff_templates[slug]" value="list" checked />
        <?php else: ?>
          <input type="radio" name="staff_templates[slug]" value="list" />
        <?php endif; ?>
        List
      </p>

      <p>
        <?php if($current_template == 'grid'): ?>
          <input type="radio" name="staff_templates[slug]" value="grid" checked />
        <?php else: ?>
          <input type="radio" name="staff_templates[slug]" value="grid" />
        <?php endif; ?>
        Grid
      </p>

      <p>
        <?php if($current_template == 'custom'): ?>
          <input type="radio" name="staff_templates[slug]" value="custom" checked />
        <?php else: ?>
          <input type="radio" name="staff_templates[slug]" value="custom">
        <?php endif; ?>
        Custom
      </p>

      <?php if($current_template == 'custom'): ?>
        <div id="custom-template">
      <?php else: ?>
        <div id="custom-template" style="display:none;">
      <?php endif;?>
        <p>
          Accepted Shortcodes - These MUST be used inside the <code>[staff_loop]</code> shortcodes:
        </p>

        <p>
          <code>[name]</code>,
          <code>[photo_url]</code>,
          <code>[position]</code>,
          <code>[email]</code>,
          <code>[phone]</code>,
          <code>[bio]</code>,
          <code>[website]</code>,
          <code>[category]</code>
        </p>

        <p>
          These will only return string values. If you would like to return pre-formatted headers (using &lt;h3&gt; tags), links, and paragraphs, use these:
        </p>

        <p>
          <code>[name_header]</code>,
          <code>[photo]</code>,
          <code>[email_link]</code>,
          <code>[bio_paragraph]</code>,
          <code>[website_link]</code>
        </p>

        <label for="staff_templates[html]">Staff Page HTML:</label>
        <p>
          <textarea name="staff_templates[html]" rows="10" cols="50" class="large-text code"><?php echo stripslashes(get_option('staff_directory_html_template')); ?></textarea>
        </p>

        <label for="staff_templates[css]">Staff Page CSS:</label>
        <p>
          <textarea name="staff_templates[css]" rows="10" cols="50" class="large-text code"><?php echo stripslashes(get_option('staff_directory_css_template')); ?></textarea>
        </p>
      </div>

      <p>
        <input type="submit" class="button button-primary button-large" value="Save">
      </p>
    </form>

    <?php
  }

  static function help() {
    ?>

    <h2>Shortcodes</h2>

    <p>
      Use the <code>[staff-directory]</code> shortcode in a post or page to display your staff.
    </p>

    <p>
      The following parameters are accepted:
      <ul>
        <li><code>cat</code> - the staff category ID to use. (Ex: [staff-directory cat=1])</li>
        <li><code>id</code> - the ID for a single staff member. (Ex: [staff-directory id=4])</li>
        <li><code>orderby</code> - the attribute to use for ordering. Supported values are 'name' and 'ID'. (Ex: [staff-directory orderby=name])</li>
        <li><code>order</code> - the order in which to arrange the staff members. Supported values are 'asc' and 'desc'. (Ex: [staff-directory orbder=asc])</li>
      </ul>
      Note - Ordering options can be viewed here - <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters">https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters</a>
    </p>

    <h2>Template Tag</h2>

    <p>
      This plugin previsouly supported a custom template function, but it's now
      recommended to use the following if you need to hardcode a staff directory
      into a template:
      <br />
      <code>&lt;?php echo do_shortcode( '[staff-directory]' ); ?&gt;</code>
    </p>

    <?php
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
