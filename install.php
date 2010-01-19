<?php

// Check to see if Staff Directory is already installed.
// If not, create the table. Assume it's a new install
$new_install = true;

$staff_directory_table = $wpdb->prefix . 'staff_directory';
$staff_directory_categories = $wpdb->prefix . 'staff_directory_categories';
$staff_directory_config = $wpdb->prefix . 'staff_directory_config';

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
                                PRIMARY KEY (staff_id)
                        )";
   		$wpdb->get_results($sql);	
    	
    }
    
    // Check and install categories table
    if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_categories'" ) != $staff_directory_categories ){

    	$sql = "CREATE TABLE " . $staff_directory_categories . " (
                                cat_id INT(11) NOT NULL AUTO_INCREMENT ,
                                name VARCHAR(30) NOT NULL ,
                                PRIMARY KEY (cat_id)
                        )";
   		$wpdb->get_results($sql);

    }
    
    // Check and install config table
    /*if( $wpdb->get_var( "SHOW TABLES LIKE '$staff_directory_config'" ) != $staff_directory_config ){

   		// 
    	$sql = "CREATE TABLE " . $staff_directory_config . " (
                                staff_id INT(11) NOT NULL AUTO_INCREMENT ,
                                staff_name VARCHAR(30) NOT NULL ,
                                staff_position VARCHAR(30) NOT NULL ,
                                staff_email_address VARCHAR(30) NOT NULL ,
                                staff_phone_number VARCHAR(30) NOT NULL ,
                                staff_thumbnail VARCHAR(60) NOT NULL ,
                                staff_bio TEXT NOT NULL ,
                                PRIMARY KEY (staff_id)
                        )";
   		$wpdb->get_results($sql);

    }*/
}

?>