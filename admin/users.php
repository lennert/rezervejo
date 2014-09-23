<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';

            $groups = mysqli_query($link, "SELECT * FROM groups");
            while($gassoc = mysqli_fetch_assoc($groups)) {
                $groarr[] = $gassoc;
            }
            foreach ($groarr as $gids) {
                $grids[$gids['id']] = $gids['id']; 
            }

if(isset($_POST['save'])) {    foreach ($_POST as $key => $value) { ${$key} = mysqli_real_escape_string($link,$value); }
    if(array_key_exists($group, $grids)) {
        if(!isset($active) || $active != true) { 
            $userupdate = mysqli_query($link,"UPDATE users SET `groups_id` = '$group' , `mail` = '$mail' WHERE `id` = '$id'");
                if($userupdate) {$checking = 1;} else {$checking = 0;}
        }
        elseif(isset($active) && $active == true) {
            $userupdate = mysqli_query($link,"UPDATE users SET `groups_id` = '$group' , `mail` = '$mail' , `active` = '0' WHERE `id` = '$id'");
                if($userupdate) {$checking = 1;} else {$checking = 0;}
        }
          
        }
        if($checking = 1) {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "User saved",  type: "succes", autoClose: true, duration: 2 }); </script>';
        }
        else {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) . '",  type: "error", autoClose: true, duration: 2 }); </script>';
        }
}
if(isset($_POST['delete'])) {
    if(mysqli_query($link, "DELETE FROM users WHERE id = " . $_POST['id'])) {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "User deleted",  type: "succes", autoClose: true, duration: 2 }); </script>';
    }
    else {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong",  type: "error", autoClose: true, duration: 2 }); </script>';
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

        if(!isset($_GET['group'])) {
           foreach($groarr as $data) {
               echo '<p><a href="users.php?group=' . $data['id'] . '">' . $data['name'] . '</a></p>';
           }
        }
        else { $gid = $_GET['group'];
            $users = mysqli_query($link, "SELECT * FROM users WHERE groups_id = '$gid'");
            if(mysqli_num_rows($users) > 0) {
                while($userarray = mysqli_fetch_assoc($users)) {
                    $usearr[] = $userarray;
                }
                foreach($usearr as $data) {
                           ?>
        <form method="post">
            <table>
                <tr>
                    <td>
                        <?= $data['first_name'] .  ' ' . $data['last_name'] . ' (' . $data['username'] . ')';?>
                    </td>                    
                    <td>
                        <input name="mail" value="<?= $data['mail'];?>">
                    </td>
                    <td>
                        <select name="group">
                        <?php
                            foreach ($groarr as $group) {
                                echo '<option';
                                if($data['groups_id'] == $group['id']) {echo ' selected ';} 
                                echo ' value="' . $group['id'] . '">'.$group['name'].'</option>';
                            }
                        ?>
                        </select>
                    </td>
                    <td>
                        <?php
                            if($data['active'] == '0') { $a = 'checked disabled';} else { $a = '';}
                            echo 'activate?<input type="checkbox" ' . $a . ' name="active">';
                        ?> 
                        <input type="hidden" value="<?= $data['id']; ?>" name="id">
                    </td>
                    <td><input type="submit" value="save" name="save"></td><td><input type="submit" value="delete" name="delete" onclick="javascript: return show_confirm();"></td>
                </tr>
            </table>
        </form>
        <?php
                }
            } 
            else { echo 'No users in this group. <a href="users.php">Return</a>'; }
        }
        if(isset($sucnot)) {echo $sucnot;}
        ?>
       
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>