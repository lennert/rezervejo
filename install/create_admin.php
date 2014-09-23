<?php session_start();
$message = ''; $errormessage = ''; $account = ''; $messagetop = ''; $errormessagetop = '';
$username = ''; $first_name = ''; $last_name = ''; $email = ''; $disabled = '';

include_once('../db.inc.php');
        # no other accounts detected
if(isset($_POST['make_account'])) {
    $username = $_POST['username']; 
    $first_name = $_POST['first_name']; 
    $last_name = $_POST['last_name']; 
    $email = $_POST['email'];
    $i = 0;
    foreach($_POST as $value) {if(empty($value)) { $i++; } }
    if($i > 0) {$errormessage .= '<p class="red">All fields are required.</p>';}
    else {
        if($_POST['password'] != $_POST['repeat_password']) { echo 'passwd not equal';}
        else {
            $emailcheck = mysqli_query($link,"SELECT * FROM users WHERE mail LIKE '" . $_POST['email'] . "'");
            if(!$emailcheck){echo mysqli_error($link);}
            if(mysqli_num_rows($emailcheck) > 0) {$errormessage .= '<p class="red">Email already in use</p>';}
            else {
                $usernamecheck = mysqli_query($link,"SELECT * FROM users WHERE username LIKE '" . $_POST['username'] . "'");
                if(!$usernamecheck) {$errormessage .= '<p class="red">Something went wrong: ' . mysqli_error($link) . ' + ' . mysqli_errno($link) . '</p>';}
                if(mysqli_num_rows($usernamecheck) > 0) {$errormessage .= '<p class="red">Username already in use</p>';}
                else {
                    $pw = sha1($_POST['password']);
                    $query = "INSERT INTO users(username,mail,first_name,last_name,password,active,groups_id) VALUES('$username','$email','$first_name','$last_name','$pw','0','1');";
                    $createuser = mysqli_query($link,$query);
                    if(!$createuser) {$errormessage .= '<p class="red">Something went wrong: ' . mysqli_error($link) . ' + ' . mysqli_errno($link) . '</p>';}
                    else {
                        $_SESSION['user'] = $username; $_SESSION['login'] = true; $_SESSION['admin'] = true;
                        $message .= '<p class="green">User succesfully created. Now you can proceed to the <a href="settings.php">next step</a>, and enter some default settings.</p>';
                        $disabled = 'disabled';
                    }                     
                }
            }    
        }
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>setup rezervejo</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
    </head>
    <body>        
        <?= $account; ?>
        <p>
            Now, you can create an administrator account.<br />
            With this account you can login on the system and start configuring and using rezervejo.
        </p>
        <form method="post">
            <table>
                <tr><td><span class="required">*</span> username:</td><td><input required name="username" value="<?=$username;?>" <?= $disabled;?>></tr>
                <tr><td><span class="required">*</span> first name:</td><td><input required name="first_name" value="<?=$first_name;?>" <?= $disabled;?>></tr>
                <tr><td><span class="required">*</span> last name:</td><td><input required name="last_name" value="<?=$last_name;?>" <?= $disabled;?>></tr>
                <tr><td><span class="required">*</span> email:</td><td><input required type="email" name="email" value="<?=$email;?>" <?= $disabled;?>></tr>
                <tr><td><span class="required">*</span> password:</td><td><input required type="password" name="password" <?= $disabled;?>></tr>
                <tr><td><span class="required">*</span> repeat password:</td><td><input required type="password" name="repeat_password" <?= $disabled;?>></tr>
                <tr><td><input type="submit" name="make_account"></td></tr>
            </table>
        </form>    
        <?= $errormessage;?>
        <?= $message; ?>
    </body>
</html>
