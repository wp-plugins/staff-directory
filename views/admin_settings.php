<script type="text/javascript">
  jQuery(document).ready(function($){
    $('input[name="staff_templates[slug]"]').on('change', function(){
      if($(this).val() == 'custom') {
        $("#custom-template").slideDown();
      } else {
        $("#custom-template").slideUp();
      }
    })

    $('#add-new-field').on('click', function(ev){
      ev.preventDefault();
      var tr = $('<tr/>');
      tr.html($('#new-field-template').html());
      $("#add-new-field-row").before(tr);
    });

    $(document).on('click', '.remove-field', function(ev){
      ev.preventDefault();
      $(this).parent().parent().remove();
    });
  });
</script>

<style type="text/css">
  div.updated.staff-success-message {
    margin-left: 0px;
    margin-top: 20px;
  }
  #new-field-template {
    display: none;
  }
  .form-group {
    margin-bottom: 50px;
  }
</style>

<?php if($did_update_options): ?>
  <div id="message" class="updated notice notice-success is-dismissible below-h2 staff-success-message">
    <p>Settings updated.</p>
  </div>
<?php endif; ?>

<form method="post">

  <div class="form-group">
    <h2>Custom Details Fields</h2>

    <p>
      This allows you to create custom details fields for each Staff member.
      Name and bio fields are provided by default, so you don't need to add those here.
    </p>

    <table class="widefat fixed" cellspacing="0" id="staff-meta-fields">
      <thead>
        <tr>
          <th id="columnname" class="manage-column column-columnname" scope="col">Name</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Type</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Template Shortcode</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Remove</th>
        </tr>
      </thead>

      <tfoot>
        <tr>
          <th id="columnname" class="manage-column column-columnname" scope="col">Name</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Type</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Template Shortcode</th>
          <th id="columnname" class="manage-column column-columnname" scope="col">Remove</th>
        </tr>
      </tfoot>

      <tbody>
        <?php foreach(get_option('staff_meta_fields') as $field): ?>
          <tr class="column-<?php echo $field['slug']; ?>">
            <td>
              <input type="text" name="staff_meta_fields_labels[]" value="<?php echo $field['name']; ?>" />
            </td>
            <td>
              <select name="staff_meta_fields_types[]">
                <?php if($field['type'] == 'text'): ?>
                  <option value="text" selected>text field</option>
                  <option value="textarea">text area</option>
                <?php elseif($field['type'] == 'textarea'): ?>
                  <option value="text">text field</option>
                  <option value="textarea" selected>text area</option>
                <?php else: ?>
                  <option value="text">text field</option>
                  <option value="textarea">text area</option>
                <?php endif; ?>
              </select>
            </td>
            <td>
              [<?php echo $field['slug']; ?>]
            </td>
            <td>
              <a href="#" class="remove-field">Remove Field</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr id="add-new-field-row" valign="top">
          <td colspan=4>
            <a href="#" id="add-new-field">+ Add New Field</a>
          </td>
        </tr>
        <tr id="new-field-template">
          <td>
            <input type="text" name="staff_meta_fields_labels[]" />
          </td>
          <td>
            <select name="staff_meta_fields_types[]">
              <option value="text">text field</option>
              <option value="teaxtarea">text area</option>
            </select>
          </td>
          <td></td>
          <td>
            <a href="#" class="remove-field">Remove Field</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="form-group">
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
        Accepted Shortcodes are listed in the Custom Details Fields table above. These shortcodes must be contained within the <code>[staff_loop]</code> shortcodes.
      </p>

      <p>
        Preformatted shortcodes are listed below. There were more options in this list previously, but due to the addition of the Custom Details Fields above some of them were removed from the suggestions. They will still work for now, but deprecated shortcodes are marked below and will no longer work at some point in the future.
      </p>

      <ul>
        <li><code>[photo_url]</code> - the url to the featured image for the staff member</li>
        <li><code>[photo]</code> - an &lt;img&gt; tag with the featured image for the staff member</li>
        <li><code>[name]</code> - the staff member's name</li>
        <li><code>[name_header]</code> - the staff member's name with &lt;h3&gt; tags</li>
        <li><code>[bio]</code> - the staff member's bio</li>
        <li><code>[bio_paragraph]</code> - the staff member's bio with &lt;p&gt; tags</li>
        <li><code>[category]</code> - the staff member's category</li>
        <li><code>[email_link]</code> (deprecated, requires and Email field above)</li>
        <li><code>[website_link]</code> (deprecated, requires a Website field above)</li>
      </ul>

      <label for="staff_templates[html]">Staff Page HTML:</label>
      <p>
        <textarea name="staff_templates[html]" rows="10" cols="50" class="large-text code"><?php echo stripslashes(get_option('staff_directory_html_template')); ?></textarea>
      </p>

      <label for="staff_templates[css]">Staff Page CSS:</label>
      <p>
        <textarea name="staff_templates[css]" rows="10" cols="50" class="large-text code"><?php echo stripslashes(get_option('staff_directory_css_template')); ?></textarea>
      </p>
    </div>
  </div>

  <p>
    <input type="submit" class="button button-primary button-large" value="Save">
  </p>
</form>
