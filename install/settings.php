<?php session_start();
$message = ''; $errormessage = '';
$message_name = 'This name will be shown on top of every page.';
$message_email = 'This is the adress users will see when they receive an email from the system. Best pick an email to which they can reply.';
$message_logo['message'] = 'Max 1MB. Only images (jpg,png,gif)';
$message_url = 'URL where your app will be installed. e.g : http://yourdomain.com/rezervejo';
$message_terms = 'A link to your terms and agreements. e.g : http://yourdomain.com/terms.pdf or http://yourdomain.com/rules.html';
$finalmessage = '';
include_once('../db.inc.php');
include_once 'imageUpload.php';
if(isset($_POST['save'])) {
    $errornum = 0;
    foreach($_POST as $key => $value ) {
        if($key != 'save' && !empty($_POST[$key])) {
            $query = mysqli_query($link,"UPDATE settings SET value = '$value' WHERE setting = '$key'");
            if($query) {${'message_' . $key} = $key . ' is now ' . $value . ' &check;';} else {${'message_'.$key} = mysqli_error($link);$errornum++;}
        } 
    }  
    if(!empty($_FILES['logo']['name'])){     
        $name = 'logo'; 
        $maxsize = '1000000';
        $uploaddir = '../images/';
        $uploadfile = basename($_FILES[$name]['name']);
        $message_logo = imageUpload($name,$uploaddir,$uploadfile,$maxsize); 
        if($message_logo['succes'] == 1) {
            if(mysqli_query($link, "UPDATE settings SET value = 'images/" . $uploadfile . "' WHERE setting = 'logo'")){
                 $message_logo['message'] .= ' and path set in database &check;';
            }
            else {$message_logo['message'] .= mysqli_error($link); $errornum++;}
        }
        else {
            $errornum++;
        }
    }
    if($errornum == 0) {
        $finalmessage = 'Your system is ready to run. Good job! No go to your <a href="../admin/index.php">admin page</a><br />IMPORTANT: Do not forget to remove the /install folder from your server. If you leave this folder online, it is a security threat. Other user will be able to reset your database info, create administrator users, get to users data and misuse your system. You will never need to use this folder again.';
    } else { 
        $finalmessage  = 'It seem some things went wrong (' . $errornum .' warnings found). Check the form for errormessages and try again (or you can contact your system administrator).';
        $finalmessage .= '<br />Sure everything is right? You can still login in to your <a href="../admin/index.php">admin page</a> and try everything out.<br />Any additional settings can be edited in the sytem or directly in the database if needed.';
        $finalmessage .= '<br />If you continue and everything works:<br />IMPORTANT: Do not forget to remove the /install folder from your server. If you leave this folder online, it is a security threat. Other user will be able to reset your database info, create administrator users, get to users data and misuse your system. You will never need to use this folder again.';
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
        <p>
            In this final step we shall configure some default settings. This way, your system will be ready to use immediately.<br />
            After this step, you will be logged in to the system.
        </p>
        <form method="post" enctype="multipart/form-data">
            <table>
                <tr><td>Instance name:</td><td><input name="name"></td><td><?= $message_name;?></td></tr>
                <tr><td>Logo:</td><td><input name="logo" type="file" accept="image/*"></td><td><?= $message_logo['message']; ?></td></tr>
                <tr><td>Email</td><td><input name="email"></td><td><?= $message_email;?></td></tr>
                <tr><td>Url</td><td><input name="url"></td><td><?= $message_url;?></td></tr>
                <tr><td>Terms</td><td><input name="terms"></td><td><?= $message_terms;?></td></tr>
                <tr><td><input type="submit" name="save" value="save"></td></tr>    
            </table>
        </form>
        <?= $errormessage;?>
        <?= $message; ?>
        <?= $finalmessage; ?>
    </body>
</html>
