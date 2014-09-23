<?php
if(isset($_SESSION['group'])) {
//$basename = substr(strtolower(basename($_SERVER['PHP_SELF'])),0,strlen(basename($_SERVER['PHP_SELF']))-4);
$gid = $_SESSION['group']; }
//if($basename == 'products' && isset($_GET['product'])) {

if($_SESSION['login'] == 'user') {
    $rq = mysqli_query($link, "SELECT * FROM rights WHERE `group` = '$gid' AND `category` = '$category'");
    $r = mysqli_fetch_assoc($rq);
    foreach ($r as $key => $value) { ${$key} = $value; }
}