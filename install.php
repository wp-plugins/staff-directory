<?php

// Check to see if Staff Directory is already installed.
// If not, create the table. Assume it's a new install
$new_install = true;

$staff_directory_table = $wpdb->prefix . 'staff_directory';
$staff_directory_categories = $wpdb->prefix . 'staff_directory_categories';
$staff_directory_options = $wpdb->prefix . 'staff_directory_options';
$staff_directory_templates = $wpdb->prefix . 'staff_directory_templates';

$tables = $wpdb->get_results('show tables;');

foreach($tables as $tables){

    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_table'" ) != $staff_directory_table ){

    	// install the main staff directory table
    	$sql = "CREATE TABLE " . $staff_directory_table . " (
                                staff_id INT(11) NOT NULL AUTO_INCREMENT ,
                                name VARCHAR(30) NOT NULL ,
                                position VARCHAR(30) NOT NULL ,
                                email_address VARCHAR(30) NOT NULL ,
                                phone_number VARCHAR(30) NOT NULL ,
                                thumbnail VARCHAR(60) NOT NULL ,
                                bio TEXT NOT NULL ,
                                category varchar(3),
                                image varchar(100),
                                PRIMARY KEY (staff_id)
                        )";
   		$wpdb->get_results($sql);	
    	
    }
    
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_table'" ) == $staff_directory_table ){
    	
    	// Check for 'image' field and add if doesn't exist
    	/*$sql = "SHOW COLUMNS FROM $staff_directory_table WHERE FIELD = 'image'";
       	$fields = $wpdb->query($sql);
    	if($fields == ''){
    		$sql = "ALTER TABLE  `$staff_directory_table` ADD  `image` VARCHAR( 100 ) NOT NULL AFTER  `bio` ;";
    		$wpdb->get_results($sql);
    	}*/
    	// end 'image' field
    	
    	
    }
        
    // Check and install categories table
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_categories'" ) != $staff_directory_categories ){

    	$sql = "CREATE TABLE " . $staff_directory_categories . " (
                                cat_id INT(11) NOT NULL AUTO_INCREMENT ,
                                name VARCHAR(30) NOT NULL ,
                                PRIMARY KEY (cat_id)
                        )";
   		$wpdb->get_results($sql);
   	
   	
   		$sql = "INSERT INTO " . $staff_directory_categories . " (
					`cat_id` ,
					`name`
					)
					VALUES (
					NULL ,  'Uncategorized'
					);";
					
   		$wpdb->get_results($sql);

    }
        
    /*if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_table'" ) == $staff_directory_table ){
    	
    	// Check for 'show_image' field and add if doesn't exist
    	$sql = "SHOW COLUMNS FROM $staff_directory_categories WHERE FIELD = 'show_image'";
       	$fields = $wpdb->query($sql);
    	if($fields == ''){
    		$sql = "ALTER TABLE  `$staff_directory_categories` ADD  `show_image` VARCHAR( 3 ) NOT NULL AFTER  `name` ;";
    		$wpdb->get_results($sql);
    	}
    	// end 'show_image' field
    	
    	
    }*/
    
    
    // Check and install options table
    /*if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_options'" ) != $staff_directory_options ){

   		// 
    	$sql = "CREATE TABLE " . $staff_directory_options . " (
    							option_id INT(11) NOT NULL AUTO_INCREMENT ,
                                option_name VARCHAR(50) NOT NULL ,
                                option_value VARCHAR(50) NOT NULL ,
                                PRIMARY KEY (option_id)
                        )";
   		$wpdb->get_results($sql);
   		   		
   		$sql = "INSERT INTO  `" . $staff_directory_options . "` (
					`option_id` ,
					`option_name` ,
					`option_value`
					)
					VALUES (
					NULL ,  'enable_single_pages',  'no'
					);";
					
   		$wpdb->get_results($sql);
   		
    }*/
    
     // Check and install templates table
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_templates'" ) != $staff_directory_templates ){

    	$sql = "CREATE TABLE " . $staff_directory_templates . " (
    							template_id INT(11) NOT NULL AUTO_INCREMENT ,
                                template_name VARCHAR(50) NOT NULL ,
                                template_code TEXT NOT NULL ,
                                PRIMARY KEY (template_id)
                        )";
   		$wpdb->get_results($sql);
   		
   		$html = "<div class=\"staff-directory\">

[staff_loop]
					
    [name_header]
    [position]
    [email_link]
    [bio_paragraph]
					
    <div class=\"staff-directory-divider\">
    </div>
					
[/staff_loop]
					
</div>";
					
   		$sql = "INSERT INTO  `" . $staff_directory_templates . "` (
					`template_id` ,
					`template_name` ,
					`template_code`
					)
					VALUES (
					NULL ,  'staff_index_html',  '$html'
					);";
					
   		$wpdb->get_results($sql);
   		
   		$css = ".staff-directory-divider{
    border-top: solid black thin;
    width: 90%;
    margin:15px 0;
}";
							
   		$sql = "INSERT INTO  `" . $staff_directory_templates . "` (
					`template_id` ,
					`template_name` ,
					`template_code`
					)
					VALUES (
					NULL ,  'staff_index_css',  '$css'
					);";
					
   		$wpdb->get_results($sql);
    }
}

?>