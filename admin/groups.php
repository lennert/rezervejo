<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';

if(isset($_POST['save'])) { foreach($_POST as $key => $value) {${$key} = mysqli_real_escape_string($link,$value); }
    $prer = preg_match_all('/@/', $prereq);
    if($prer < 1) { $prrq = $prereq; } 
    elseif($prer == 1) { $expr = explode('@',$prereq); $prrq = $expr[1]; }
    else { $prrq = 'error'; }
    if($prrq == 'error') {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Wrong email-prereq",  type: "error", autoClose: true, duration: 2 }); </script>';
    }
    else {
       $newgroup = mysqli_query($link, "INSERT INTO groups (`name`,`prereq`) VALUES ('$name','$prrq')");
       if($newgroup) {
       $sucnot = '<script type="text/javascript"> showNotification({ message: "new group added",  type: "succes", autoClose: true, duration: 2 }); </script>';
       
       }
           else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong ' .  mysqli_error($link) . '",  type: "error", autoClose: true, duration: 2 }); </script>'; }
       }
    }

if(isset($_POST['edit'])) { foreach($_POST as $key => $value) {${$key} = mysqli_real_escape_string($link,$value); }
$prer = preg_match_all('/@/', $prereq);
    if($prer < 1) { $prrq = $prereq; } 
    elseif($prer == 1) { $expr = explode('@',$prereq); $prrq = $expr[1]; }
    else { $prrq = 'error'; }
    if($prrq == 'error') {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Wrong email-prereq",  type: "error", autoClose: true, duration: 2 }); </script>';
    }
    else {
       $newgroup = mysqli_query($link, "UPDATE groups SET `name` = '$name' , `prereq` = '$prrq' WHERE `id` = '$id'");
       if($newgroup) {
       $sucnot = '<script type="text/javascript"> showNotification({ message: "group edited",  type: "succes", autoClose: true, duration: 2 }); </script>';
       
       }
           else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong ' .  mysqli_error($link) . '",  type: "error", autoClose: true, duration: 2 }); </script>'; }
       }
}
if(isset($_POST['delete'])) { foreach($_POST as $key => $value) {${$key} = mysqli_real_escape_string($link,$value); }
    $deletegroup = mysqli_query($link,"DELETE FROM groups WHERE `id` = '$id'");
    if($deletegroup) {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Group deleted",  type: "succes", autoClose: true, duration: 2 }); </script>';
    }
    else {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong ' .  mysqli_error($link) . '",  type: "error", autoClose: true, duration: 2 }); </script>';
    }
}

$allgroups = mysqli_query($link, "SELECT * FROM groups");
while($grps = mysqli_fetch_assoc($allgroups)) {
    $groupsarray[] = $grps;
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
            <?php echo $header; ?>
        </div>
        <p>
            <b>Prereq</b> = email extension to automatically add a new user to that group. Example: <i>john.doe@teacher.com</i> automatically in group 'teacher'. <i>jane.doe@student.com</i> automatically in group 'student'. <br />
            Adresses who do not meet a prereq will be added to the default group. In the future it will be possible to block these adresses automatically and select a default group.<br />
            This field is optional, and can be left blank.<br />
            Do not type the <b>@</b>-sign. This will be taken care of by the system.<br /> 
            Users can always manually be changed to another group on the <a href="users.php">users-page</a>.
        </p><hr />
<?php 
        foreach ($groupsarray as $name => $value) { 
            if($value['name'] == 'admin' || $value['name'] == 'default') { $d = 'disabled';  }  else {$d = '';}
                    ?>
            <form method="post">
                <table>
                    <tr>
                        <td>Name: <input name="name" value="<?= $value['name']; ?>" <?php if(isset($d)) {echo $d;} ?>></td>
                        <td>Prereq: @ <input name="prereq" value="<?= $value['prereq'];?>"></td>
                        <td>
                            <input type="hidden" name="id" value="<?= $value['id']; ?>">
                            <input type="submit" name="edit" value="save" <?php if(isset($d)) {echo $d;} ?>>
                            <input type="submit" name="delete" value="delete" onclick="javascript:return show_confirm()" <?php if(isset($d)) {echo $d;} ?>></td>
                    </tr>
                </table>
            </form>
        
        <?php }
?><hr />
        New group:
                    <form method="post">
                <table>
                    <tr>
                        <td>Name: <input name="name" placeholder="name"></td>
                        <td>Prereq: @ <input placeholder="example.com" name="prereq" ></td>
                        <td><input type="submit" name="save" value="save"></td>
                    </tr>
                </table>
            </form>
        <?php if(isset($sucnot)) {echo $sucnot;} ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>