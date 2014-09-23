<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';

if(isset($_POST['delete'])) 
{
    $resid = $_POST['resid'];
    $deleteres = mysqli_query($link, "DELETE FROM reservations WHERE id = '$resid'");
    if(!$deleteres) {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) .  '",  type: "error", autoClose: true, duration: 2 }); </script>';
    }
    else {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Reservation deleted",  type: "succes", autoClose: true, duration: 2 }); </script>';
    }
}

$getreservations = mysqli_query($link, "SELECT *, reservations.id as resid, users.username as username, `product`.`name` as pname  FROM `reservations` JOIN  `users` on reservations.`user` = users.`id` JOIN `product` ON `reservations`.`product` = `product`.`id` WHERE `from` >= curdate() ORDER BY `from`,`user`,`until`");
if(mysqli_num_rows($getreservations) < 1) {$numres = 0;} else {$numres = 1;}
while ($resarr = mysqli_fetch_assoc($getreservations)) {
    $allres[] = $resarr; 
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
        <script>
            function show_confirm() {
                return confirm("Sure you want to delete this record?");
            }
        </script>
    </head>
    <body>
        <div id="header">
            <?php echo $header; ?></div>
        <?php
        if($numres > 0) {
            //echo '<pre>'; print_r($allres); echo '</pre>';
            foreach ($allres as $reservation) {
                ?>
        <form method="post">
            <input type="hidden" name="resid" value="<?= $reservation['resid']; ?>">
                <?php echo $reservation['first_name'] . ' ' . $reservation['last_name'] .  ' : ' . $reservation['pname'] . ' (' . $reservation['number'] . ') [' . $reservation['from'] . '-' . $reservation['until'] . ']'; ?>
            <input type="submit" value="delete" name="delete" onclick="javascript:return show_confirm()">
        </form>
        <?php
            }
        }
        else {
            echo 'no reservations';
        }
        ?>
        <?php if(isset($sucnot)) {echo $sucnot;} ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>