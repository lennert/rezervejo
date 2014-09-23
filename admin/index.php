<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
    </head>
    <body>
        <div id="header">
            <?php echo $header; ?></div>
        <?php
        foreach ($pages as $name => $link) {
            echo '<p><a href="'. $link . '">' . $name . '</a></p>';
        }
        ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>