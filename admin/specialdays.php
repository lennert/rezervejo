<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
                <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <script src="../js/jquery_notification_v.1.js" type="text/javascript"></script>
        <link href="../js/jquery_notification.css" rel="stylesheet" type="text/css"/>
        <script>
            function show_confirm() {
                return confirm("Sure you want to delete this record?");
            }
        </script>
    </head>
<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';

if(isset($_POST['save'])) {foreach($_POST as $key => $value) {${$key} = $value;}
    $dinsert = date('Y-m-d',strtotime($date));
    if($dinsert == '1970-01-01' || strtotime($dinsert) < strtotime(date('Y-m-d',time()))) {
       $sucnot = '<script type="text/javascript"> showNotification({ message: "Wrong date",  type: "error", autoClose: true, duration: 2 }); </script>'; 
    }
    else {
        if(mysqli_query($link, "UPDATE days_special SET `day` = '$dinsert' , `open` = '$open' WHERE `id` = '$id'")) {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "Special date ' . $date .  ' saved",  type: "succes", autoClose: true, duration: 2 }); </script>';
        }
        else {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "' . mysqli_error($link) .  '",  type: "error", autoClose: true, duration: 2 }); </script>';
        }
    }    
}
if(isset($_POST['delete'])) { foreach($_POST as $key => $value) {${$key} = $value;}
    if(mysqli_query($link, "DELETE FROM days_special WHERE id = '$id'")){
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Special date ' . $date . ' deleted",  type: "succes", autoClose: true, duration: 2 }); </script>';
    }
    else {
        $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong",  type: "error", autoClose: true, duration: 2 }); </script>';        
    }
}

if(isset($_POST['savenew'])) { foreach($_POST as $key => $value) {${$key} = $value;}
    $dinsert = date('Y-m-d',strtotime($date));
    if($dinsert == '1970-01-01' || strtotime($dinsert) < strtotime(date('Y-m-d',time()))) {
       $sucnot = '<script type="text/javascript"> showNotification({ message: "Wrong date",  type: "error", autoClose: true, duration: 2 }); </script>'; 
    }
    else {
        if(mysqli_query($link, "INSERT INTO days_special (`day`,`open`) VALUES ('$dinsert','$open')")) {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "Special date ' . $date .  ' saved",  type: "succes", autoClose: true, duration: 2 }); </script>';
        }
        else {
            $sucnot = '<script type="text/javascript"> showNotification({ message: "' . mysqli_error($link) .  '",  type: "error", autoClose: true, duration: 2 }); </script>';
        }
    }
}
?>
    <body>
        <div id="header">
            <?php echo $header; ?></div>
        <?php
        $specialsdays = mysqli_query($link, "SELECT * FROM days_special ORDER BY day");
        while($sdarr = mysqli_fetch_assoc($specialsdays)) {
            $specdays[] = $sdarr;
        }
        if(isset($specdays)) {
        foreach ($specdays as $id => $data) { 
        ?>
        <form method="post">
            <table>
                <tr>
                    <td><input name="id" type="hidden" value="<?=$data['id'];?>"><input name="date" value="<?= $data['day'];?>"></td>
                    <td>
                        <select name="open">
                            <option value="1"<?php if($data['open'] == 1) { echo ' selected ';}?>>OPEN</option>
                            <option value="0"<?php if($data['open'] == 0) { echo ' selected ';}?>>CLOSED</option>
                        </select>
                    </td>
                    <td><input type="submit" name="save" value="save"></td>
                    <td><input type="submit" name="delete" value="delete" onclick="javascript: return show_confirm();"></td>
                </tr>
            </table>
        </form>
        <?php
        } }
        ?>
        New Date:
                <form method="post">
            <table>
                <tr><td>Date (dd-mm-yyyy)</td><td>open/closed</td><td>save</td></tr>
                <tr>
                    <td><input name="date" placeholder="dd-mm-yyyy"></td>
                    <td>
                        <select name="open">
                            <option value="1">OPEN</option>
                            <option value="0" selected>CLOSED</option>
                        </select>
                    </td>
                    <td><input type="submit" name="savenew" value="save"></td>
                </tr>
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