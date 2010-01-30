<?php

require_once( dirname (__FILE__).'/admin-functions.php' );
require_once( dirname (__FILE__).'/categories.php' );
require_once( dirname (__FILE__).'/templates.php' );
require_once( dirname (__FILE__).'/options.php' );
require_once( dirname (__FILE__).'/uninstall.php' );



// Hook for adding admin menus
add_action('admin_menu', 'staff_directory_add_pages');

// action function for above hook
function staff_directory_add_pages() {

    // Add a new top-level menu (ill-advised):
    add_menu_page('Staff Directory', 'Staff Directory', 'edit_pages', 'staff-directory', 'staff_directory_main_admin');

    // Categories Page
    add_submenu_page('staff-directory', 'Categories', 'Categories', 'edit_pages', 'staff-directory-categories', 'staff_directory_categories');
    
    // Templates Page
    add_submenu_page('staff-directory', 'Templates', 'Templates', 'edit_pages', 'staff-directory-templates', 'staff_directory_templates');
	
	// Options Page
    //add_submenu_page('staff-directory', 'Options', 'Options', 'administrator', 'staff-directory-options', 'staff_directory_options');
        
     // Uninstall Page
    //add_submenu_page('staff-directory', 'Uninstall Staff Directory', 'Uninstall', 'administrator', 'uninstall-staff-directory', 'uninstall_staff_directory');

}



// Main Admin Page - this handles add, edit and delete
// These four functions can be found in admin-functions.php
function staff_directory_main_admin() {

	// setup the main admin page with a table
	if(!isset($_GET['action'])){
		staff_directory_main_admin_page();
	}
	
	
	
	
	
	// setup the add new page
	if(isset($_GET['action']) && $_GET['action'] == 'addStaffMember'){
		add_new_staff_member();
	}
	
	
	
	
	// setup the edit page
	if(isset($_GET['action']) && $_GET['action'] == 'edit'){
		edit_staff_member();
	}
	
	
	
	
	
	// setup the delete page
	if(isset($_GET['action']) && $_GET['action'] == 'delete'){
		delete_staff_member();
	}
}

?>
