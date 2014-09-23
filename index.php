<?php session_start();
 include_once 'logincheck.inc';
//print_r($_SESSION);
?>

<!--
rezervejo (esperanto for reservation - because esperanto was designed for universal use, and so is this)
Library Tool to make the reservation of products easier and more user-friendly
The original idea came when we needed a tool for students and teachers to easily reserve some tablets, though it can be used for a wide range of products.

Developed by Lennert Holvoet for Artevelde University College in Ghent

Feel free to use this code and even modify it. It's all yours.
Of course, modifications or on your own risk.
Some updates may erase your modifications, especially if you hard-code info in the system.

If you have developed some nifty functions or expansions to rezervejo, feel free to let me know at lennert.holvoet@arteveldehs.be, maybe they can be added as an update or a plugin.
-->
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
        $cats = mysqli_query($link, "SELECT * FROM category");
        while($allcats = mysqli_fetch_assoc($cats)) {
            ?><a href="category.php?category=<?= $allcats['id'] ; ?>" >
        <div class='item' 
             <?php
             if($allcats['img'] != null) { ?>
             style="background-image: url('<?= $allcats['img'];?>');">
             <?php } else { ?>
            style="background-image: url('images/rezervejo.png');">
             <?php } ?>
            <h4 class="text"><?= $allcats['name']; ?></h4>            
        </div></a>
        <?php
        }
        ?>
    </body>
</html>