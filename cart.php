<?php session_start();
 include_once 'logincheck.inc'; 
 if(isset($_POST['delete'])) {
     $pid = $_POST['prodid'];
     unset($_SESSION['cart'][$pid]);
     $sucnot = '<script type="text/javascript"> showNotification({ message: "Item removed from cart",  type: "succes", autoClose: true, duration: 2 }); </script>';
 }
 if(isset($_POST['order'])) {
     $errors = 0;
     foreach($_SESSION['cart'] as $productid => $cartitem) {
         $userid = $_SESSION['uid']; $from = date('Y-m-d',  strtotime($_SESSION['cart'][$productid]['from'])); $until = date('Y-m-d',  strtotime($_SESSION['cart'][$productid]['until'])); $number = $_SESSION['cart'][$productid]['number'];
         $confir = mysqli_query($link, "INSERT INTO reservations (`product`,`user`,`from`,`until`,`number`) VALUES ('$productid','$userid','$from','$until','$number')");
         if(!$confir) {
             $errors++;
             $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) .  '",  type: "error", autoClose: true, duration: 2 }); </script>';
         }
     }
      if($errors == 0) {
        $userinfo = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE id = '$userid'"));
        $to = $userinfo['mail'];
        $subject = 'You confirmation from ' . $setting['name'];
        $message = 'You just ordered: ' . "\r\n";
        foreach($_SESSION['cart'] as $productid => $cartitem) {
        $message .= $cartitem['number'] .  ' &times ' . $cartitem['name'] .  ' [ ' . $cartitem['from']. ' - ' . $cartitem['until'] .  ' ]' . "\r\n";
        }
        $message .= 'Please bring this email when you pick up your reservation';
        $headers = 'From: ' . $setting['email']  . "\r\n" .
        'Reply-To: ' . $setting['email'] . "\r\n";
        //echo $to . ' ' . $message;
        mail($to, $subject, $message, $headers);
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Your reservation was placed. Check your mail for the confirmation.",  type: "succes", autoClose: true, duration: 2 }); </script>';
        unset($_SESSION['cart']);
      }
      else {
          $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong.",  type: "succes", autoClose: true, duration: 2 }); </script>';
      }
 }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - cart</title>
        <link type="text/css" rel="stylesheet" href="style.css" />
        <script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <link href="js/jquery_notification.css" rel="stylesheet" type="text/css"/>
        <script src="js/jquery_notification_v.1.js" type="text/javascript"></script> 
        <script>
            function show_confirm() {
                return confirm("Sure you want to delete this record?");
            }
        </script>
    </head>
    <body>
        <div id="header"><?= $header; ?></div>
        <?php
        if($_SESSION['login'] == 'nologin') {echo 'You need to be logged in to acces your cart';}
        else {
            if(isset($_SESSION['cart'])) {
                foreach($_SESSION['cart'] as $productid => $cartitem) {
                ?>
        <form method="post">
            <input type="hidden" value="<?=$productid;?>" name="prodid">
            <p><b><?= $cartitem['number'] ;?> &times; <?= $cartitem['name'];?></b>  [ <?= $cartitem['from']; ?> - <?= $cartitem['until'];?> ] <input type="submit" value="delete from cart" name="delete" onclick="javascript:return show_confirm()"></p>
        </form>

                <?php
                } ?>
        <hr />
        <form method="post">
            <input type="submit" value="order" name="order">
        </form>
                <?php
            }
            elseif(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo 'No items in your cart';
            }
        }
        ?>
        <hr />
        <form method="post">
            <input type="submit" value="order" name="order">
        </form>
      <?php if(isset($sucnot)) {echo $sucnot;} ?>
    </body>
</html>