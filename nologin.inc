<?php
$err = ''; include_once 'db.inc.php';
//$_SESSION['icomefrom'] = $_SERVER['PHP_SELF'];
if(isset($_POST['login'])) {
    $i = 0; foreach($_POST as $value) { if(empty($value)) {$i++;}}
    if($i > 0) { $err = '<i>all fields required</i>';}
    else { 
        $username = mysqli_real_escape_string($link,$_POST['username']);
        $query = mysqli_query($link, "SELECT * ,users.id as userid, groups.name as gname FROM users JOIN groups ON users.groups_id = groups.id WHERE username = '$username'");
        if(mysqli_num_rows($query) != 1) {$err = '<i>login failed</i>';}
        else {
            $check = mysqli_fetch_assoc($query);
            $password = sha1($_POST['password']);
            $group = $check['groups_id'];
            if($check['password'] != $password || $check['active'] != 0) {$err = '<i>login failed</i>';}
            else {
                if($group != 1) {
                $_SESSION['login'] = 'user'; $_SESSION['group'] = $group; $_SESSION['uid'] = $check['userid']; $_SESSION['username'] = $check['username'] ; header('Location:' . $_SERVER['REQUEST_URI']);             
                }
                else {
                $_SESSION['login'] = 'admin'; $_SESSION['group'] = $group; $_SESSION['uid'] = $check['userid']; header('Location:' . $_SERVER['REQUEST_URI']);
                }
            }
        }
    }
}
$header = $err . '<form method="post" style="display: inline"><input name="username" placeholder="username" required> <input type="password" name="password" placeholder="password" required> <input type="submit" name="login" value="login"></form> (No account? <a href="createaccount.php">create one</a>)';
