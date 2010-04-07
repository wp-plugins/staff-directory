<?php

function staff_directory_main_admin_page(){
	
		$output = "";
		global $wpdb;
		$staff_directory_categories = $wpdb->prefix . 'staff_directory_categories';
		
		if(isset($_POST['import_wp_users']) && $_POST['import_wp_users'] == "yes"){
			import_wordpress_users();
		}
		
		if(isset($_POST['edit-action']) && $_POST['edit-action'] == 'delete'){
			$select = $_POST['select'];
			$count = count($select);
			for($i = 0; $i < $count; $i++){
					$photo = $wpdb->get_var("SELECT `photo` FROM " . STAFF_DIRECTORY_TABLE . " WHERE `staff_id` = " . $select[$i]);
					if($photo != '' AND is_file(STAFF_PHOTOS_DIRECTORY . $photo)) unlink(STAFF_PHOTOS_DIRECTORY . $photo);
					$sql = "DELETE FROM " . STAFF_DIRECTORY_TABLE . " WHERE `staff_id` = " . $select[$i];
					$wpdb->get_results($sql);
			}
		}
		
		
		check_uploads_directory();
		
		
		$output .= "<h2>Staff Directory</h2>";
		$output .= "<p>Please visit the <a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=about-staff-directory\">about page</a> for instructions.</p>";
		$addNewURL = get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&action=addStaffMember";
		$output .= "<div style=\"padding:15px;\">";
		$output .= "<p><a href=\"" . $addNewURL . "\" style=\"float:left; margin:15px 10px 0 0\">+ Add New Staff Member</a>";
		
		$output .="<form method=\"post\" style=\"float:left\">
					<input name=\"import_wp_users\" value=\"yes\" style=\"display:none\">
					<input type=\"submit\" value=\"Import Existing Wordpress Users\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
					</form>
					</p>
		
					";
		if(isset($_GET['page'])) $page = $_GET['page'];
		if(isset($_GET['orderby'])){
			$orderby = $_GET['orderby'];
		}else{
			$orderby = null;
		}
		if(isset($_GET['order'])){
			$order = $_GET['order'];
		}else{
			$order = null;
		}
		
		
		$output .= "<form style=\"float:right\" method=\"GET\" action=\"\">
					<input type=\"hidden\" name=\"page\" value=\"$page\">
					<input type=\"hidden\" name=\"orderby\" value=\"$orderby\">
					<input type=\"hidden\" name=\"order\" value=\"$order\">
					<select name=\"filter\">";
		$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories");
		$output .= "<option></option>";
		foreach($categories as $category){
			$output .= "<option value=\"" . $category->cat_id . "\">" . $category->name . "</option>";
		}
		$output .= "</select>
					<input type=\"submit\" value=\"Filter\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
					</form>";

		$output .= "<script type=\"text/javascript\">
function selectAll(x) {
for(var i=0,l=x.form.length; i<l; i++)
if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
x.form[i].checked=x.form[i].checked?false:true
}
</script>
					<form method=\"post\">
					<table class=\"widefat\" style=\"margin-bottom:10px\">
						<thead>
							<tr>
								<th>Select</th>
								<th>Photo</th>
								<th>ID</th>
								<th>Name <a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&orderby=name&order=ASC\">&darr;</a> <a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&orderby=name&order=DESC\">&uarr;</a></th>
								<th>Postion</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Bio</th>
								<th>Category <a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&orderby=category&order=ASC\">&darr;</a> <a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory&orderby=category&order=DESC\">&uarr;</a></th>
								<th>Actions</th>
							</tr>
						</thead>	
		";
		if(isset($_GET['orderby'])){
			$orderby = $_GET['orderby'];
		}else{
			$orderby = null;
		}
		
		if(isset($_GET['order'])){
			$order = $_GET['order'];
		}else{
			$order = null;
		}
		
		if(isset($_GET['filter'])){
			$filter = $_GET['filter'];
		}else{
			$filter = null;
		}
		
		$all_staff = get_all_staff($orderby, $order, $filter);
			if(isset($all_staff)){
				foreach($all_staff as $staff){
					$output .= "<tr>";
					$output .= "<td><input type=\"checkbox\" name=\"select[]\" value=\"" . $staff->staff_id . "\"></td>";
					if($staff->photo != ''){
						$output .= "<td><img src=\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\" width=50></td>";
					}else{
						$output .= "<td></td>";
					}
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
			}
		$output .= "<tfoot>
						<tr>
							<th>&nbsp;</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
					</table>
					<input type=\"checkbox\" name=\"sAll\" onclick=\"selectAll(this)\" style=\"margin-left: 10px\" /> Select / Deselect All <br />
					<select name=\"edit-action\">
						<option>
						<option value=\"delete\">Delete Selected
					</select>
					<input type=\"submit\" value=\"Submit\" style=\"padding:5px 10px; margin:10px 10px; border:thin solid gray\">
					</form>";
		$output .= "</div>";
		
		echo $output;

}


function add_new_staff_member(){

		global $wpdb;
		$id = $_GET['id'];
		
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
		
		$staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
		$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories_table");
		
		$output = "<h2>Add New Staff Member</h2>";
		$output .= "<div style=\"padding:15px; width:400px\">";
		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
		
		if(isset($_GET['action']) && $_GET['action'] == 'addStaffMember' && !isset($_POST['name-to-add'])){
			
			$output .= "<form method=\"post\" enctype=\"multipart/form-data\">
						<table class=\"widefat\">
							<thead>
								<tr>
									<th>Enter Staff Details</th>
									<th></th>
								</tr>
							</thead>
							
							<tr>
								<td>Photo:</td>
								<td><input name=\"staff-photo\" type=\"file\"></td>
							</tr>
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
								<td><textarea name=\"bio\" cols=38 rows=8></textarea></td>
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

			$existing_staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE email_address = '" . $_POST['email_address'] . "'");
			if($existing_staff->email == $_POST['email_address']){
				//$output .= "<p>Email already exists</p>";
			}
			
			global $wpdb;
			$staff_directory_table = $wpdb->prefix . 'staff_directory';
			$sql = "INSERT INTO " . STAFF_DIRECTORY_TABLE . " (
					`staff_id` ,
					`name` ,
					`position` ,
					`email_address` ,
					`phone_number` ,
					`photo` ,
					`bio` ,
					`category`
					)
					VALUES (
					'null',  '" . $_POST['name-to-add'] . "',  '" . $_POST['position'] . "',  '" . $_POST['email_address'] . "',  '" . $_POST['phone_number'] . "',  '" . $_FILES['staff-photo']['name'] . "',  '" . $_POST['bio'] . "',  '" . $_POST['category'] . "'
					);";
					
			
			$wpdb->get_results($sql);
			
			$output .= $_POST['name-to-add'] . " was added to the directory.";
			//$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
			if(isset($_FILES['staff-photo']) AND $_FILES['staff-photo']['name'] != ''){
				$uploadfile = STAFF_PHOTOS_DIRECTORY . basename($_FILES['staff-photo']['name']);
				move_uploaded_file($_FILES['staff-photo']['tmp_name'], $uploadfile);
			}
					
		}
		$output .= "</div>";
		echo $output;

}

function edit_staff_member(){

		global $wpdb;
		$id = $_GET['id'];
		
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$staff_directory_categories_table = $wpdb->prefix . 'staff_directory_categories';
		
		$staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
		$categories = $wpdb->get_results("SELECT * FROM $staff_directory_categories_table");
		
		$output = "<h2>Edit Staff Member - " . $staff->name . "</h2>";
		$output .= "<div style=\"padding:15px; width:400px\">";
		$output .= "<p><a href=\"" . get_bloginfo('wpurl') . "/wp-admin/admin.php?page=staff-directory\">Back to Staff</a></p>";
		
		
		if(isset($_POST['name'])){
			if($_FILES['staff-photo']['name'] != ''){
				$photo = $_FILES['staff-photo']['name'];
			}else{
				$photo = $staff->photo;
			}
			
			$sql = "UPDATE  " . STAFF_DIRECTORY_TABLE . " SET  `name` =  '" . $_POST['name'] . "', position = '" . $_POST['position'] . "', email_address = '" . 			$_POST['email_address'] . "', phone_number = '" . $_POST['phone_number'] . "', bio = '" . $_POST['bio'] . "', category = '" . $_POST['category'] . "', photo ='" . $photo . "' 			WHERE  `staff_id` =  " . $id . ";";
			
			$wpdb->get_results($sql);
			$staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
			
			if(isset($_FILES['staff-photo']) AND $_FILES['staff-photo']['name'] != ''){
				$uploadfile = STAFF_PHOTOS_DIRECTORY . basename($_FILES['staff-photo']['name']);
				move_uploaded_file($_FILES['staff-photo']['tmp_name'], $uploadfile);
			}
						
			$output .= "<p>" . $staff->name . " Updated</p>";
			
		}
	
			
			$output .= "<form method=\"post\" enctype=\"multipart/form-data\">
						<table class=\"widefat\">
						<thead>
							<tr>
								<th>Enter Staff Details</th>
								<th></th>
							</tr>
						</thead>
											
						<tr>
							<td>Photo:</td>
							<td><input name=\"staff-photo\" type=\"file\">";
			if($staff->photo != '') $output .= "<img src =\"" . get_bloginfo('wpurl') . "/wp-content/uploads/staff-photos/" . $staff->photo . "\" width=200>";		
			$output .= "</td>
						</tr>
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
							<td><textarea name=\"bio\" cols=38 rows=8>" . $staff->bio . "</textarea></td>
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
							
	echo $output;
			
			
}

function delete_staff_member(){
	global $wpdb;
		$staff_directory_table = $wpdb->prefix . 'staff_directory';
		$id = $_GET['id'];
		$staff = $wpdb->get_row("SELECT * FROM " . STAFF_DIRECTORY_TABLE . " WHERE staff_id = '$id'");
			
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
			
			$photo = $wpdb->get_var("SELECT `photo` FROM " . STAFF_DIRECTORY_TABLE . " WHERE `staff_id` = $id");
			if($photo != '' AND is_file(STAFF_PHOTOS_DIRECTORY . $photo)) unlink(STAFF_PHOTOS_DIRECTORY . $photo);
			
			$sql = "DELETE FROM `" . STAFF_DIRECTORY_TABLE . "` WHERE `staff_id` = " . $id . ";";
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
	
	// Create photos upload directory
	if(!file_exists(STAFF_PHOTOS_DIRECTORY) OR !is_dir(STAFF_PHOTOS_DIRECTORY)){
		if(mkdir(STAFF_PHOTOS_DIRECTORY, 0777)){
			return true;
		}else{
			$error_message = "<p style=\"margin: 40px 10px 10px 10px; padding:5px; font-weight:bold; background: #ff6633;\">We could not create the uploads/staff=photos directory. Please do so manually (<a href=\"#\">What does this mean?</a>)</p>";
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
		$sql = "INSERT INTO " . STAFF_DIRECTORY_TABLE . " (
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