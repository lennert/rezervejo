<?php session_start();

 include_once 'logincheck.inc';
 if($_SESSION['login'] == 'nologin') {
     
     if(isset($_POST['create'])) {

         $count = 0;
         foreach($_POST as $key => $value) {
            ${$key} = mysqli_real_escape_string($link,$value);
            if(empty($value)) {$count++;}
         }
         if($count > 0) {
             $sucnot = '<script type="text/javascript"> showNotification({ message: "Please fill out all fields",  type: "error", autoClose: true, duration: 2 }); </script>';
         }
         else {
            $mailcheck = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if(!preg_match($mailcheck, $email)) {
                $sucnot = '<script type="text/javascript"> showNotification({ message: "Invalid email",  type: "error", autoClose: true, duration: 2 }); </script>';
            }
            elseif(preg_match($mailcheck, $email)) {
                $checkusers = mysqli_query($link, "SELECT * FROM users WHERE `username` = '$username' OR `mail` = '$email'");
                if(mysqli_num_rows($checkusers) != 0) {
                    $sucnot = '<script type="text/javascript"> showNotification({ message: "Username/Email already in use",  type: "error", autoClose: true, duration: 2 }); </script>';
                }
                elseif($password === $passwordcheck) {
                            $inspw = sha1($password);
                            $rand = '';
                            $ascii = array(array('min' => '48','max'=>'57'), array('min'=> '65', 'max' => '90') , array('min' => '97' , 'max' => '122'));
                            $tc = $email . '' . $last_name . '' . $first_name . '' . $username;
                            $max = strlen($tc);
                                for($i = 0; $i < $max ; $i++) {
                                $key = rand(0,2);
                                $rand .= chr(mt_rand($ascii[$key]['min'], $ascii[$key]['max']));
                                }
                                $pr = explode('@',$email); $prereq = $pr['1'];
                                $getgroup = mysqli_query($link, "SELECT * FROM groups WHERE prereq = '$prereq'");
                                if(mysqli_num_rows($getgroup) == 1) {
                                    $selgroup = mysqli_fetch_assoc($getgroup);
                                    $groupsid = $selgroup['id'];
                                }
                                else { $groupsid = 2; }
                            $activate = sha1($rand);
                            $insert = mysqli_query($link,"INSERT INTO users (username,first_name,last_name,mail,password,active,groups_id) VALUES ('$username','$first_name','$last_name','$email','$inspw','$activate','$groupsid')");
                            if($insert) {
                                
                                echo $activateurl =  $setting['url'] . '/activateaccount.php?activate=' . $activate;
                                $to      = $email;
                                $subject = 'Activate your account for ' . $setting['name'];
                                $message = 'Click this link to activate your account: ' . $activateurl;
                                $headers = 'From: ' . $setting['email']  . "\r\n" .
                                    'Reply-To: ' . $setting['email'] . "\r\n";

                                mail($to, $subject, $message, $headers);

                                $sucnot = '<script type="text/javascript"> showNotification({ message: "Account created - check your email to activate it",  type: "succes", autoClose: true, duration: 2 }); </script>';
                            } else {echo mysqli_error($link) . ' - '  . mysqli_errno($link);}
                            }
            }
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
        <form method="post">
            <table>
                <tr>
                <tr><td>Username<span class="required">*</span></td>
                    <td colspan=""><input name="username" placeholder="username" required></td>
                </tr>
                <tr>
                    <td>First name<span class="required">*</span></td>
                    <td><input name="first_name" placeholder="first name" required></td>
                    <td>Last name<span class="required">*</span></td>
                    <td><input name="last_name" placeholder="last name" required></td>
                </tr>
                <tr>
                    <td>Email<span class="required">*</span></td>
                    <td><input type="email" name="email" placeholder="email" required></td>
                </tr>
                <tr>
                    <td>Password<span class="required">*</span></td>
                    <td><input type="password" name="password" placeholder="password" required></td>
                    <td>Repeat password<span class="required">*</span></td>
                    <td><input type="password" name="passwordcheck" placeholder="repeat password" required></td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="terms"></td><td>I agree to the <a href="<?= $setting['terms']; ?>">terms and agreements</a>.</td>
                </tr>
                <tr>
                    <td><input type="submit" name="create" value="create account"></td>
                </tr>
            </table>
        </form>
        
        <?php 
            if(isset($sucnot)) { echo $sucnot;}
        ?>
    </body>
</html>
<?php
 } else{ header("Location: ./index.php"); }
?>