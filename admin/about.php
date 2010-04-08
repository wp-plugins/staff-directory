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
				<br /><br />
				</p>
				<p>
				<h3>Staff Ordering</h3>
				This is the first phase of the ordering that is to come.
				<br />
				I have added two more parameters into the shortcodes.
				<br />
				You can now add 'orderby' and 'oder' into the [staff-directory] tag.
				<br /><br />
				<b>Example:</b>
				<br />
				<b>[staff-directory orderby=name order=asc]</b> - this will order your staff by their name, in ascending order.
				<br />
				<b>[staff-directory orderby=name order=desc]</b> - this will order your staff by their name, in descending order.
				<br /><br />
				You can also use the 'cat' parameter along with these.
				<br /><br />
				<b>Example:</b>
				<br />
				<b>[staff-directory cat=3 orderby=name order=asc]</b> - this will return all of the staff in the given category (ID 3), order by name in ascending order.
				</p>
				<br />
				<p>
				<h3>Template Tag</h3>
				You can also place this tag in a template to dsiplay your staff directory:
				<br /><br />
				&lt;?php if(function_exists('staff_directory')){echo staff_directory();} ?&gt;
				</p>
				<br />
				<p>
				<h3>Upcoming Features</h3>
				<li>Ordering Feature</li>
				<li>Improved UI</li>
				<li>It's been requested that I set up a GIThub repository for this. I am looking into it.</li>
				</p>";
    $output .= "</div>";
    
    echo $output;
}

?>