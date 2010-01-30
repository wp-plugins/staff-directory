<?php
function staff_directory_options() {
    $output = "<h2>Staff Directory Settings</h2>";
    $output .= "<div style=\"padding:15px;\">";
    
    $output .= get_staff_directory_options();
    
    $output .= "</div>";
    
    echo $output;
}

?>