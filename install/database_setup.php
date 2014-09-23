<?php
function create_tables($link,$array) {
    $count = 0;
    $mess = '<ul style="list-style-type:none;">';
    foreach ($array as $name => $query) {
        $mess .= '<li>Working on ' . $name;
        $create = mysqli_query($link,$query);
        if(!$create) { $mess .= ' <span class="red">&times; ' . mysqli_error($link) . '</span>'; }
        else {$mess .= ' <span class="green">&#10003;</span>';$count++;}        
        $mess .= '</li>';
    }
    $mess .= '</ul>';
    if($count == count($array)) {$mess .= '<span class="green">Database works fine. <a href="create_admin.php">Next step</a></span>';}
    else {$mess .= '<span class="red">Somewhere, something went wrong. Try again or contact a system administrator</span>'; }
    return $mess;
}