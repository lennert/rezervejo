<?php session_start();
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo - admin - categories</title>
        <link type="text/css" href="../style.css" rel="stylesheet" />
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
    $deletequery = mysqli_query($link, "DELETE FROM category WHERE id = $id");
    if($deletequery) { $sucnot = '<script type="text/javascript"> showNotification({ message: "Category deleted",  type: "succes", autoClose: true, duration: 2 }); </script>'; }
    else { $msg = mysqli_error($link); }
}
if(isset($_POST['edit'])) { 
     $i = 0;
    foreach($_POST as $key => $value) { ${$key} = $value; if(empty($value) && $value != 'edit') {$i++;} else {} }
    if($i === 0) {
        $editcat = mysqli_query($link, "UPDATE category SET name = '$name' , description = '$description' WHERE id = '$id'");
        if($editcat) {
        if(!empty($_FILES['image']['name'])){     
        $imgname = 'image'; 
        $maxsize = '1000000';
        $uploaddir = '../images/';
        $uploadfile = basename($_FILES[$imgname]['name']);
        $message_logo = imageUpload($imgname,$uploaddir,$uploadfile,$maxsize); 
        if($message_logo['succes'] == 1) {
            if(mysqli_query($link, "UPDATE category SET img = 'images/" . $uploadfile . "' WHERE id = '$id'")){
                 $sucnot = '<script type="text/javascript"> showNotification({ message: "Added a new category",  type: "succes", autoClose: true, duration: 2 }); </script>';
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
        $addcat = mysqli_query($link, "INSERT INTO category (name,description) VALUES ('$name','$description')");
        if($addcat) {
        if(!empty($_FILES['image']['name'])){     
        $imgname = 'image'; 
        $maxsize = '1000000';
        $uploaddir = '../images/';
        $uploadfile = basename($_FILES[$imgname]['name']);
        $message_logo = imageUpload($imgname,$uploaddir,$uploadfile,$maxsize); 
        if($message_logo['succes'] == 1) {
            if(mysqli_query($link, "UPDATE category SET img = 'images/" . $uploadfile . "' WHERE name = '$name'")){
                 $sucnot = '<script type="text/javascript"> showNotification({ message: "Added a new category",  type: "succes", autoClose: true, duration: 2 }); </script>';
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
$cat = mysqli_query($link,"SELECT * FROM category");
while($cats = mysqli_fetch_assoc($cat)) {
    ?> 
    <form method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td><input type="hidden" name="id" value="<?= $cats['id'];?>"><input type="text" value="<?= $cats['name'];?>" name="name"></td>
                <td><input  type="text" value="<?= $cats['description'];?>" name="description"></td>
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