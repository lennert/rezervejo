<?php session_start();
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin - products</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
        <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
        <link href="../js/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <script src="../js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="../js/jquery_notification_v.1.js" type="text/javascript"></script>
        <link href="../js/jquery_notification.css" rel="stylesheet" type="text/css"/>
        <script>
            function show_confirm() {
                return confirm("Sure you want to delete this record?");
            }
        </script>
<?php
if($_SESSION['login'] == 'admin') {
include_once '../db.inc.php';
include_once './admin_settings.inc';include '../imgUpload.php';
$msg = '';
if(isset($_POST['delete'])) {
    foreach ($_POST as $key => $value) { ${$key} = $value;}
    $deletequery = mysqli_query($link, "DELETE FROM product WHERE id = $id");
    if($deletequery) { $sucnot = '<script type="text/javascript"> showNotification({ message: "Category deleted",  type: "succes", autoClose: true, duration: 2 }); </script>'; }
    else { $msg = mysqli_error($link); }
}
if(isset($_POST['edit'])) { 
     $i = 0;
    foreach($_POST as $key => $value) { ${$key} = $value; if(empty($value) && $value != 'edit') {$i++;} else {} }
    if($i === 0) {
        $editcat = mysqli_query($link, "UPDATE product SET name = '$name' , description = '$description', category = '$cats' , `number` = '$number'   WHERE id = '$id'");
        if($editcat) {
        if(!empty($_FILES['image']['name'])){     
        $imgname = 'image'; 
        $maxsize = '1000000';
        $uploaddir = '../images/';
        $uploadfile = basename($_FILES[$imgname]['name']);
        $message_logo = imageUpload($imgname,$uploaddir,$uploadfile,$maxsize); 
        if($message_logo['succes'] == 1) {
            if(mysqli_query($link, "UPDATE product SET img = 'images/" . $uploadfile . "' WHERE id = '$id'")){
                 $sucnot = '<script type="text/javascript"> showNotification({ message: "Added a new product",  type: "succes", autoClose: true, duration: 2 }); </script>';
            }
          else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Image upload failed ' . mysqli_error($link) .'",  type: "error", autoClose: true, duration: 4 }); </script>';}
        } else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Image upload failed ' . $message_logo['message'] .'",  type: "error", autoClose: true, duration: 4 }); </script>';}
        }
        }
    }
}

if(isset($_POST['new'])) {
    $i = 0;
    foreach($_POST as $key => $value) { ${$key} = $value; if(empty($value) && $value != 'new') {$i++;} else {} }
    if($i === 0) {
        $addcat = mysqli_query($link, "INSERT INTO product (name,description,category,number) VALUES ('$name','$description','$cats','$number')");
        if($addcat) {
        if(!empty($_FILES['image']['name'])){     
        $imgname = 'image'; 
        $maxsize = '1000000';
        $uploaddir = '../images/';
        $uploadfile = basename($_FILES[$imgname]['name']);
        $message_logo = imageUpload($imgname,$uploaddir,$uploadfile,$maxsize); 
        if($message_logo['succes'] == 1) {
            if(mysqli_query($link, "UPDATE product SET img = 'images/" . $uploadfile . "' WHERE name = '$name'")){
                 $sucnot = '<script type="text/javascript"> showNotification({ message: "Added a new product",  type: "succes", autoClose: true, duration: 2 }); </script>';
            }
          else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Image upload failed ' . mysqli_error($link) .'",  type: "error", autoClose: true, duration: 4 }); </script>';}
        } else { $sucnot = '<script type="text/javascript"> showNotification({ message: "Image upload failed ' . $message_logo['message'] .'",  type: "error", autoClose: true, duration: 4 }); </script>';}
    }  
        } else {
                $sucnot = '<script type="text/javascript"> showNotification({ message: "Something went wrong ' .  mysqli_error($link) . '",  type: "error", autoClose: true, duration: 4 }); </script>';
            }
        }
       
    else {$sucnot = '<script type="text/javascript"> showNotification({ message: "Please fill out all the fields",  type: "error", autoClose: true, duration: 2 }); </script>';}
}
?>

        
    </head>
    <body>
        <div id="header">
            <?= $header; ?>
            </div>
   
        <table>
            <tr><td>
                Category
                </td><td>Description</td>
                <td>Edit</td><td>Delete</td></tr>  </table>        
        
<?php 
$allcats = mysqli_query($link, "SELECT * FROM CATEGORY"); while($allcat = mysqli_fetch_assoc($allcats)) {
    $catdd[$allcat['id']] = $allcat['name'];
}
$cat = mysqli_query($link,"SELECT *, category.`name` as cname FROM category JOIN product ON product.category = category.id");
while($cats = mysqli_fetch_assoc($cat)) {
    ?> 
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><input type="hidden" name="id" value="<?= $cats['id'];?>"><input type="text" value="<?= $cats['name'];?>" name="name"></td>
                <td><input  type="text" value="<?= $cats['description'];?>" name="description"></td>
                <td><input type="text" value="<?= $cats['number']; ?>" name="number"></td>
                <td><select name="cats">
                <?php 
                    foreach ($catdd as $key => $value) {
                        echo '<option value="' . $key . '" ';
                        if($cats['category'] == $key) { echo 'selected';}
                        echo ' >' . $value . '</option>';
                    }
                ?></select></td>
                <td><input name="image" type="file" accept="image/*"></td>
                <td><input class="order" type="submit" value="edit" name="edit"></td>
                <td><input class="order" type="submit" value="&times;" name="delete" onclick="javascript: return show_confirm();"></td>
            </tr>  
        </table>
    </form>
<?php } ?>
        <i>to keep the same image, just ignore the upload field</i>
        <hr />
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><input type="text" placeholder="name" name="name"></td>
                <td><input  type="text" placeholder="description" name="description"></td>
                <td><input type="text" placeholder="number" name="number"></td>
                <td><select name="cats">
                <?php 
                    foreach ($catdd as $key => $value) { echo '<option value="' . $key . '">' . $value . '</option>';}
                ?>
                </select></td>
                <td><input name="image" type="file" accept="image/*"></td>
                <td><input class="order" type="submit" value="new" name="new"></td>
            </tr>
        </table>  
    </form>
    <?= $msg; ?>        
        </body>
</html>
<?php
if(isset($sucnot)) {echo $sucnot; }
?>
<?php
} 
else {header('Location:../index.php');}
?>