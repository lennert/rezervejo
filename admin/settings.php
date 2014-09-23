<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';
// edit -> text/filter voor toegelaten waarde
if(isset($_POST['save'])) {
    if(!empty($_POST)) {
        foreach ($_POST as $key => $value) { ${$key} = mysqli_real_escape_string($link,$value) ;}
        $updatesetting = mysqli_query($link, "UPDATE settings SET value = '$settingvalue' WHERE setting = '$settingname'"); }
        if(!$updatesetting) {$sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) . ' ",  type: "error", autoClose: true, duration: 2 }); </script>';}
        else {$sucnot = '<script type="text/javascript"> showNotification({ message: "Setting succefully edited",  type: "succes", autoClose: true, duration: 2 }); </script>'; }
}

$getsettings = mysqli_query($link, "SELECT * FROM settings");
while($settingsarray = mysqli_fetch_assoc($getsettings)) {
    $settingedit[] = $settingsarray;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
        <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <link href="../js/jquery_notification.css" rel="stylesheet" type="text/css"/>
        <script src="../js/jquery_notification_v.1.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="header">
            <?php echo $header; ?></div>
<?php
        foreach ($settingedit as $settings) {
        ?>
        <form method="post">
            <table>
                <tr>
                    <td><?= $settings['setting'];?><input type="hidden" value="<?= $settings['setting'];?>" name="settingname"></td>
                    <td><input name="settingvalue" value="<?= $settings['value']; ?>"></td>
                    <td><input type="submit" name="save" value="save"></td>
                </tr>
            </table>
        </form>
        <?php
        }
    
?>
        <?php if(isset($sucnot)) {echo $sucnot;} ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>