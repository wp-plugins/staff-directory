<?php

function staff_directory_main_admin_page(){
	
		$output = "";
		global $wpdb;
		
		if(isset($_POST['import_wp_users']) && $_POST['import_wp_users'] == "yes"){
			import_wordpress_users();
		}
		$output .= "<h2>Staff Directory</h2>";
		
		//check_uploads_directory();
		
		$output .= "<p style=\"margin-top:25px\">This plugin is developed by <a href=\"http://www.89designs.net\">Adam Tootle</a><br>
					You can visit the plugin homepage <a href=\"http://www.89designs.net/2010/01/staff-directory/\">here</a>. Please feel free to post any questions there.<br>
					This plugin is still in a beta version, so I am open to any feedback or suggestions you can offer. Thanks!
					<h3>Usage</h3>
					<p>Staff Directory uses shortcodes to display you staff members.<br>
					To use these shortcodes, you just paste them into any post or page like you would place any normal content.
					
					<p>Here is a list of accepted shortcodes and their functions:</p>
					<p><b>[staff-directory]</b> - displays your full directory</p>
					<p><b>[staff-directory cat=1]</b> - displays a given category. Make sure to set 'cat' to the id of the desired category.</p>
					<p><b>[staff-directory id=1]</b> - displays a single staff member. Make sure to set 'id' to the id of the desired staff member.</p>
					
					</p>
					</p>";
					
		$addNewURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&action=addStaffMember";
		$output .= "<div style=\"padding:15px;\">";
		$output .= "<p><a href=\"" . $addNewURL . "\" style=\"float:left; margin:15px 10px 0 0\">+ Add New Staff Member</a>";
		
		$output .="<form method=\"post\">
					<input name=\"import_wp_users\" value=\"yes\" style=\"display:none\">
					<input type=\"submit\" value=\"Import Existing Wordpress Users\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
					</form>
					</p>
		
		";
		$output .= "<table class=\"widefat\">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Postion</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Bio</th>
								<th>Category</th>
								<th>Actions</th>
							</tr>
						</thead>	
		";
		
		$staff = get_all_staff();
			foreach($staff as $staff){
				$output .= "<tr>";
				$output .= "<td>" . $staff->staff_id . "</td>";
				$output .= "<td>" . $staff->name . "</td>";
				$output .= "<td>" . $staff->position . "</td>";
				$output .= "<td>" . $staff->email_address . "</td>";
				$output .= "<td>" . $staff->phone_number . "</td>";
				
				if(strlen($staff->bio)>15){$staff->bio = substr($staff->bio, 0, 15) . "...";}
				$output .= "<td>" . substr($staff->bio, 0, 40) . "</td>";	
				
				$category_name = get_single_category_name_by_id($staff->category);		
				$output .= "<td>" . $category_name . "</td>";
				
				$editURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&action=edit&id=" . $staff->staff_id;
				$deleteURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&action=delete&id=" . $staff->staff_id;
				$output .= "<td><a href=\"" . $editURL . "\">Edit</a> | <a href=\"" . $deleteURL . "\">Delete</a></td>";
				$output .= "</tr>";
			}
		$output .= "</table>";
		$output .= "</div>";
		
		echo $output;

}


function add_new_staff_member(){

		global $wpdb;
		$id = $_GET['id'];
		
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
		
		$staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
		$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories_table");
		
		$output = "<h2>Add New Staff Member</h2>";
		$output .= "<div style=\"padding:15px; width:400px\">";
		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
		
		if(isset($_GET['action']) && $_GET['action'] == 'addStaffMember' && !isset($_POST['name-to-add'])){
			
			$output .= "<form method=\"post\">
						<table class=\"widefat\">
							<thead>
								<tr>
									<th>Enter Staff Details</th>
									<th></th>
								</tr>
							</thead>
							
							<tr>
								<td>Name:</td>
								<td><input name=\"name-to-add\"></td>
							</tr>
							<tr>
								<td>Position:</td>
								<td><input name=\"position\"></td>
							</tr>
							<tr>
								<td>Email:</td>
								<td><input name=\"email_address\"></td>
							</tr>
							<tr>
								<td>Phone Number:</td>
								<td><input name=\"phone_number\"></td>
							</tr>
							<tr>
								<td>Bio:</td>
								<td><textarea name=\"bio\"></textarea></td>
							</tr>
							<tr>
								<td>Category:</td>
								<td>
									<select name=\"category\">";
				foreach($categories as $category){
				
					$output .= "<option value=\"" . $category->cat_id . "\">" . $category->name . "</option>";
				
				}
				$output .= "</select>
								</td>
							</tr>
							<tr>
								<td><input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\"></td>
								<td></td>
							</tr>
							";
			
			
			$output .= "</form>
						</table>";
		}
		
		if(isset($_POST['name-to-add'])){

			$existing_staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE email_address = '" . $_POST['email_address'] . "'");
			if($existing_staff->email == $_POST['email_address']){
				$output .= "<p>Email already exists</p>";
			}
			
			global $wpdb;
			$staff_directory_table = $wpdb->prefix . 'staff_directory';
			$sql = "INSERT INTO " . $staff_directory_table . " (
					`staff_id` ,
					`name` ,
					`position` ,
					`email_address` ,
					`phone_number` ,
					`thumbnail` ,
					`bio` ,
					`category`
					)
					VALUES (
					'null',  '" . $_POST['name-to-add'] . "',  '" . $_POST['position'] . "',  '" . $_POST['email_address'] . "',  '" . $_POST['phone_number'] . "',  '',  '" . $_POST['bio'] . "',  '" . $_POST['category'] . "'
					);";
					
			
			$wpdb->get_results($sql);
			
			
			$output .= $_POST['name-to-add'] . " was added to the directory.";
			//$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
		
		}
		$output .= "</div>";
		echo $output;

}

function edit_staff_member(){

global $wpdb;
		$id = $_GET['id'];
		
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
		
		$staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
		$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories_table");
		
		$output = "<h2>Edit Staff Member - " . $staff->name . "</h2>";
		$output .= "<div style=\"padding:15px; width:400px\">";
		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
		
		if(!isset($_POST['name'])){
	
			
			$output .= "<form method=\"post\">
						<table class=\"widefat\">
							<thead>
								<tr>
									<th>Enter Staff Details</th>
									<th></th>
								</tr>
							</thead>
							
							<tr>
								<td>Name:</td>
								<td><input name=\"name\" value=\"" . $staff->name . "\"></td>
							</tr>
							<tr>
								<td>Position:</td>
								<td><input name=\"position\" value=\"" . $staff->position . "\"></td>
							</tr>
							<tr>
								<td>Email:</td>
								<td><input name=\"email_address\" value=\"" . $staff->email_address . "\"></td>
							</tr>
							<tr>
								<td>Phone Number:</td>
								<td><input name=\"phone_number\" value=\"" . $staff->phone_number . "\"></td>
							</tr>
							<tr>
								<td>Bio:</td>
								<td><textarea name=\"bio\">" . $staff->bio . "</textarea></td>
							</tr>
							<tr>
								<td>Category:</td>
								<td>
									<select name=\"category\">";
				foreach($categories as $category){
					if($staff->category == $category->cat_id){
						$output .= "<option selected=\"selected\" value=\"" . $category->cat_id . "\">" . $category->name . "</option>";
					}else{
						$output .= "<option value=\"" . $category->cat_id . "\">" . $category->name . "</option>";
					}
				
				}
				
				$output .= "</select>
								</td>
							</tr>
							<tr>
								<td><input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\"></td>
								<td></td>
							</tr>
							</table>
							</form>
							";
			
			
		}
		
		if(isset($_POST['name'])){
			$sql = "UPDATE  " . $staff_directory_table . " SET  `name` =  '" . $_POST['name'] . "', position = '" . $_POST['position'] . "', email_address = '" . 			$_POST['email_address'] . "', phone_number = '" . $_POST['phone_number'] . "', bio = '" . $_POST['bio'] . "', category = '" . $_POST['category'] . "' 			WHERE  `staff_id` =  " . $id . ";";
			
			$wpdb->get_results($sql);
			$staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
			
			$output .= "<p>" . $staff->name . " Updated</p>
						<form method=\"post\">
						<table class=\"widefat\">
							<thead>
								<tr>
									<th>Enter Staff Details</th>
									<th></th>
								</tr>
							</thead>
							
							<tr>
								<td>Name:</td>
								<td><input name=\"name-to-add\" value=\"" . $staff->name . "\"></td>
							</tr>
							<tr>
								<td>Position:</td>
								<td><input name=\"position\" value=\"" . $staff->position . "\"></td>
							</tr>
							<tr>
								<td>Email:</td>
								<td><input name=\"email_address\" value=\"" . $staff->email_address . "\"></td>
							</tr>
							<tr>
								<td>Phone Number:</td>
								<td><input name=\"phone_number\" value=\"" . $staff->phone_number . "\"></td>
							</tr>
							<tr>
								<td>Bio:</td>
								<td><textarea name=\"bio\">" . $staff->bio . "</textarea></td>
							</tr>
							<tr>
								<td>Category:</td>
								<td>
									<select name=\"category\">";
				foreach($categories as $category){
					if($staff->category == $category->name){
						$output .= "<option selected=\"selected\">" . $category->name . "</option>";
					}else{
						$output .= "<option>" . $category->name . "</option>";
					}
				
				}
				$output .= "</select>
								</td>
							</tr>
							<tr>
								<td><input type=\"submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\"></td>
								<td></td>
							</tr>
							";
		}
		
		$output .= "</form>
					</table>";
		$output .= "</div>";
		
		echo $output;

}

function delete_staff_member(){
	global $wpdb;
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$id = $_GET['id'];
		$staff = $wpdb->get_row("SELECT * FROM $staff_directory_table WHERE staff_id = '$id'");
			
		if(!isset($_POST['confirm-delete'])){	
			
			$output = "<h2>Delete Staff Member</h2>";
			$output .= "<div style=\"padding:15px;\">";
			$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
			$output .= "Are you sure you want to delete " . $staff->name . "? This cannot be undone!";
			$output .= "<form method=\"post\">
						<input name=\"confirm-delete\" value=\"yes\" style=\"display:none\">
						<input name=\"staff-member-to-delete-nice-name\" value=\"" . $staff->name . "\" style=\"display:none\">
						<input type=\"submit\" value=\"Yes\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
						</form>
						";
			
			$output .= "</div>";
			
			echo $output;
		}
		if(isset($_POST['confirm-delete']) && $_POST['confirm-delete'] == 'yes'){

			$sql = "DELETE FROM `" . $staff_directory_table . "` WHERE `staff_id` = " . $id . ";";
			$wpdb->get_results($sql);
			$output = "<h2>Delete Staff Member</h2>";
			$output .= "<div style=\"padding:15px;\">";
			$output .= "<p>" . $_POST['staff-member-to-delete-nice-name'] . " was deleted</p>";
			$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
			
			$output .= "</div>";
			
			echo $output;		
		}
}



function get_single_category_name_by_id($id){
	global $wpdb;
	$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
	$category = $wpdb->get_row("SELECT * FROM $staff_directory_categories_table WHERE cat_id = '$id';");
	return $category->name;
}



function check_uploads_directory(){
			
	$upload_directory = ABSPATH . "wp-content/uploads/staff";
	if(file_exists($upload_directory) && is_dir($upload_directory)){
		return true;
	}else{
		$error_message = "<p style=\"margin: 40px 10px 10px 10px; padding:5px; font-weight:bold; background: #ff6633;\">We couldn't create the staff/ directory in the uploads/. Please do so manually (<a href=\"#\">What does this mean?</a>)</p>";
		
		if(!mkdir($upload_directory)){
			 echo $error_message;
		}
	}	
}



function import_wordpress_users(){

	global $wpdb;
	$user_table = $wpdb->prefix . "users";
	$user_meta_table = $wpdb->prefix . "usermeta";
	$staff_directory_table = $wpdb->prefix . 'staff_directory';
	$sql = "SELECT * FROM $user_table";
	$users = $wpdb->get_results($sql);
	
	foreach($users as $user){
		$description = $wpdb->get_var("SELECT meta_value FROM $user_meta_table WHERE user_id = $user->ID AND meta_key = 'description'");
		$description = mysql_real_escape_string($description);
		$sql = "INSERT INTO " . $staff_directory_table . " (
					`staff_id` ,
					`name` ,
					`position` ,
					`email_address` ,
					`phone_number` ,
					`thumbnail` ,
					`bio` ,
					`category`
					)
					VALUES (
					'null',  '$user->display_name', '', '$user->user_email', '', '', '$description', '1'
		);";
				
		$wpdb->get_results($sql);
	}

}

function get_staff_directory_options(){
	global $wpdb;
	$staff_directory_options = $wpdb->prefix . 'staff_directory_options';
	$output = "";
	
	$output .= "<form method=\"post\">";
	$output .= "<p><h3>Enable Widget:</h3> </p>";
	$output .= "<p><h3>Enable Single Pages:</h3> </p>";
	$output .= "</form>";

	return $output;
}

?>