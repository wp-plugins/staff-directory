<?php
function about_staff_directory() {
    $output = "<h2>About Staff Directory Settings</h2>";
    $output .= "<div style=\"padding:15px;\">";
    $output .= "<p>Plugin version: 0.8b
				<br />
 				Developed by: Adam Tootle.
				<br />
				Plugin homepage: <a href=\"http://www.89designs.net/2010/01/staff-directory/\">http://www.89designs.net/2010/01/staff-directory/</a>
				<br />
				Donate link: <a href=\"http://www.89designs.net/donate\">http://www.89designs.net/donate</a>
				</p>
				<p>
				<h3>Instructions:</h3>
				User the following shortcodes in your post to display your staff directory or staff members:
				<br /><br />
				<b>[staff-directory]</b> will display your full staff directory.
				<br /><br />
				<b>[staff-directory cat=x]</b> will display each staff member in the category with the id specified. Replace x and use like so: [staff-directory cat =1]
				<br /><br />
				<b>[staff-directory id=x]</b> will display a single staff member, specified by the id. Works like the category tag: [staff-directory id=3]
				</p>";
    $output .= "</div>";
    
    echo $output;
}

?>