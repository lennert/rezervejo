<?php session_start();
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';
   
if(isset($_POST['savenew'])) {
    foreach ($_POST as $key => $value) {
        if(empty($value) || $value == null) {${$key} = 0;} else {${$key} =  mysqli_real_escape_string($link,$value);} 
    }
    
    $insertright = mysqli_query($link, "INSERT INTO rights (`group`,category,maxtime,maxbefore,maxuntil,maxitems) VALUES ('$group','$category','$maxtime','$maxbefore','$maxuntil','$maxitems')");
    if(!$insertright) {$sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) . ' ",  type: "error", autoClose: true, duration: 2 }); </script>';}
    else {$sucnot = '<script type="text/javascript"> showNotification({ message: "New rights added",  type: "succes", autoClose: true, duration: 2 }); </script>'; }
}

if(isset($_POST['save'])) {
    foreach ($_POST as $key => $value) {
    if(empty($value) || $value == null) {${$key} = 0;} else {${$key} =  mysqli_real_escape_string($link,$value);} 
    }
    
    $updateright = mysqli_query($link, "UPDATE rights SET maxtime = '$maxtime',maxbefore = '$maxbefore' , maxuntil = '$maxuntil' , maxitems = '$maxitems' WHERE `id` = '$rightid'");
    if(!$updateright) {$sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) . ' ",  type: "error", autoClose: true, duration: 2 }); </script>';}
    else {$sucnot = '<script type="text/javascript"> showNotification({ message: "Rights succefully edited",  type: "succes", autoClose: true, duration: 2 }); </script>'; }

}

if(isset($_POST['delete'])) {
    $deleteright = mysqli_query($link, "DELETE FROM rights WHERE id = " . $_POST['rightid']);
    if(!$deleteright) {$sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong : ' . mysqli_error($link) . ' ",  type: "error", autoClose: true, duration: 2 }); </script>';}
    else {$sucnot = '<script type="text/javascript"> showNotification({ message: "Rights succefully deleted",  type: "succes", autoClose: true, duration: 2 }); </script>'; }
}

$selectrights = mysqli_query($link, "SELECT *,rights.id as rightsid, groups.name as groupname, category.`name` as categoryname FROM rights JOIN groups ON rights.`group` = groups.id JOIN category ON rights.category = category.`id` ORDER BY rightsid");
while($rightsarray = mysqli_fetch_assoc($selectrights)) {
    $rights[] = $rightsarray;
}
$selectcat = mysqli_query($link, "SELECT * FROM category");
while($catarray = mysqli_fetch_assoc($selectcat)) {
    $cats[] = $catarray;
}
$selectgroups = mysqli_query($link, "SELECT * FROM groups");
while($groupsarray = mysqli_fetch_assoc($selectgroups)) {
    $groups[] = $groupsarray;
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
        <p>
            Legend:<br />
            Max. time keeping: how long can a user keep an item (in days)<br />
            Max. time before - Max. time until : how long in advance can a user reserve an item (in days)<br />
            <i>eg: if today is 23-09-2014 and before = 2, until = 5, then this user can select a start date from 25-09 [23+2] until 30-09 [25+5]</i><br />          
            Max. items: how many items, from one category, can a user reserve at one time<br />
            If no rights are added to a category, only an admin can reserve those objects. Other users can see the availability.
        </p>
<?php
        foreach ($rights as $right) {
?>
        <form method="post">
            <table>
                <tr>
                    <td><b><?= $right['categoryname'];?> : </b></td>
                    <td><b><?= $right['groupname'];?></b></td>
                </tr>
                <tr>
                    <td>Max. time keeping:</td><td><input name="maxtime" value="<?= $right['maxtime'];?>"></td>
                    <td>Max. time before:</td><td><input name="maxbefore" value="<?= $right['maxbefore'];?>"></td>
                    <td>Max. time until:</td><td><input name="maxuntil" value="<?= $right['maxuntil'];?>"></td>
                    <td>Max. items:</td><td><input name="maxitems" value="<?= $right['maxitems'];?>"></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="rightid" value="<?= $right['rightsid'] ;?>"><input type="submit" value="save" name="save"></td>
                    <td><input type="submit" value="delete" name="delete" onclick="javascript:return show_confirm()"></td>
                </tr>
            </table>
        </form>
<?php
        }
?>
        <hr />
        New:
                <form method="post">
            <table>
                <tr>
                    <td><select name="category"><?php
                        foreach ($cats as $cat) {
                            echo '<option value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                         }
                    ?></select></td>
                    <td><select name="group"><?php
                        foreach ($groups as $group) {
                            echo '<option value="' . $group['id'] . '">' . $group['name'] . '</option>';
                         }
                    ?></select></td>
                </tr>
                <tr>
                    <td>Max. time:</td><td><input name="maxtime"></td>
                    <td>Max. time before:</td><td><input name="maxbefore"></td>
                    <td>Max. time keeping:</td><td><input name="maxuntil"></td>
                    <td>Max. items:</td><td><input name="maxitems"></td>
                </tr>
                <tr>
                    <td><input type="submit" value="save" name="savenew"></td>
                </tr>
            </table>
        </form> <?php if(isset($sucnot)) {echo $sucnot;} ?>
    </body>
</html>
<?php
} 
else {header('Location:../index.php');}
?>