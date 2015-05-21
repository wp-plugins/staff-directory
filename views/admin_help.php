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
