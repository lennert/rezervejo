<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';

if(isset($_POST['save'])) {
   foreach($_POST['open'] as $day => $data) {
       if($data == 0 || $data == 1){
       if(mysqli_query($link,"UPDATE days SET open = '$data' WHERE day_nr = '$day'")) {
           $sucnot = '<script type="text/javascript"> showNotification({ message: "Openingdays edited",  type: "succes", autoClose: true, duration: 2 }); </script>';
       }
       else {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong",  type: "error", autoClose: true, duration: 2 }); </script>';}
       }
       else {
           $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong",  type: "error", autoClose: true, duration: 2 }); </script>';
       }
   }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
        <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="../js/jquery_notification_v.1.js" type="text/javascript"></script>
        <link href="../js/jquery_notification.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="header">
            <?php echo $header; ?></div>
        <form method="post">
            <table>
                <tr><th>Day</th><th>open/closed</th></tr>
                <?php 
for ($i = 0; $i <=6 ; $i++) {

    $days = mysqli_query($link, "SELECT * FROM days WHERE day_nr = $i");
    $daysarr = mysqli_fetch_assoc($days);

echo '<tr><td>';
echo date('l',  strtotime("Sunday + $i days"));    
//echo '<input name="'.$i.'[]" type="hidden" value="' . $i . '">';
echo '</td><td><select name="open[]"><option';
if($daysarr['open'] == 1) { echo ' selected ';}
echo ' value="1">OPEN</option><option';
if($daysarr['open'] == 0) { echo ' selected ';}
echo ' value="0">CLOSED</option></select></td>';
echo '</tr>';
}
                
?>
                <tr><td><input type="submit" value="save" name="save"></td></tr>
            </table>
        </form>
        <?php
        if(isset($sucnot)) {echo $sucnot;}
        ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>