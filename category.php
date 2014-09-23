<?php session_start();
 include_once 'logincheck.inc';

if(!isset($_GET['category'])) { header('Location: index.php'); }

    $catid = $_GET['category'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo</title>
        <link type="text/css" rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div id="header"><?= $header; ?></div>
        <?php 
        $prods = mysqli_query($link, "SELECT * FROM product WHERE category = $catid");
        while($allprods = mysqli_fetch_assoc($prods)) {
            ?>
       <a   href="products.php?product=<?= $allprods['id'] ; ?>">
        <div class='item' 
                      <?php
             if($allprods['img'] != null) { ?>
             style="background-image: url('<?= $allprods['img'];?>');">
             <?php } else { ?>
            style="background-image: url('images/rezervejo.png');">
             <?php } ?>    

             
                 <h4 class="text"><?= $allprods['name'] ; ?></h4>
                 <p class="text"><?=  $allprods['description'] ; ?></p>
             
        </div>
       </a> 
        <?php
        }
        ?>
    </body>
</html>