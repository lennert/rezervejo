<?php
if(!isset($_SESSION['login'])) {$_SESSION['login'] = 'nologin';}
    switch($_SESSION['login']) {
        case 'nologin': include_once 'nologin.inc'; break;
        case 'user': include_once 'user.inc'; break; 
        case 'admin': include_once 'admin.inc'; break;
        default : include_once 'nologin.inc'; break;
    }
$load_settings = mysqli_query($link, "SELECT * FROM settings");
while($settings_array = mysqli_fetch_assoc($load_settings)) {
    $setting[$settings_array['setting']] = $settings_array['value'];
}