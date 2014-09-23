<?php session_start();
include_once 'logincheck.inc';
if($_SESSION['login'] == 'nologin') {
    if(isset($_POST['setactive'])) {
        $userid = $_POST['userid'];
        $setactive = mysqli_query($link, "UPDATE users SET active = 0 WHERE id = '$userid'");
        if($setactive) {
            header("Location:index.php");
        }
        else{
            $sucnot = '<script type="text/javascript"> showNotification({ message: "Please try again or contact ' . $setting['email'] .  '",  type: "error", autoClose: true, duration: 5 }); </script>';
        }
    }
    
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo</title>
        <script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <link href="js/jquery_notification.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery_notification_v.1.js" type="text/javascript"></script> 
                <link type="text/css" href="style.css" rel="stylesheet" />

    </head>
    <body>
        <div id="header"><?= $header; ?></div>
     
        <?php 
            if(!isset($_GET['activate'])) {
                $sucnot = '<script type="text/javascript"> showNotification({ message: "Please use the full link from your email",  type: "error", autoClose: true, duration: 5 }); </script>';
            }
            else {
                $activationcode = $_GET['activate'];
                $getaccount = mysqli_query($link, "SELECT * FROM users WHERE `active` = '$activationcode'");
                if(mysqli_num_rows($getaccount) != 1) {
                    $sucnot = '<script type="text/javascript"> showNotification({ message: "Please use the full link from your email",  type: "error", autoClose: true, duration: 5 }); </script>';
                }
                else {
                    $accountinfo = mysqli_fetch_assoc($getaccount);
                ?>
        Hello <?= $accountinfo['first_name'] . ' ' . $accountinfo['last_name'];?>,<br />
        you registered with username <?= $accountinfo['username']; ?> and email <?= $accountinfo['mail'];?>.<br />
        At the moment you can't change these yourself, though an update for this application is on it's way.<br />
        If this is a problem, or you face problems with your account, please contact <a href="mailto:<?= $setting['email'];?>"><?= $setting['email'];?></a><br />
        Otherwise, you can go ahead and activate this account. after activation, you will be sent to the homepage where you can login with your credentials.
        <form method="post">
            <input type="submit" name="setactive" value="Activate account">
            <input type="hidden" value="<?= $accountinfo['id'];?>" name="userid">
        </form>
        <?php
                }
            }
            if(isset($sucnot)) { echo $sucnot;}
        ?>
    </body>
</html>
<?php
 } else{ header("Location: ./index.php"); }
?>