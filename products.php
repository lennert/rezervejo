<?php session_start();
include_once 'logincheck.inc'; include_once 'functions.php'; 
if(!isset($_GET['product'])) { header('Location: index.php'); }
$prodid = $_GET['product'];

        $prods = mysqli_query($link, "SELECT * FROM product WHERE id = $prodid");
        while($allprods = mysqli_fetch_assoc($prods)) {
            foreach ($allprods as $key => $value) {${$key} = $value;}           
        } 
        include_once 'rights.inc'; 

if($_SESSION['login'] == 'nologin') {
     $maxbefore = 0;
     $maxuntil = 0;
     $maxtime = 0;
     $maxitems = 0;
}
elseif($_SESSION['login'] == 'admin') {
     $maxbefore = 0;
     $maxuntil = 60;
     $maxtime = 31;
     $maxitems = $number; 
    }
    

//FOR DATES: create array 'from' & 'until' > in select with foreach + if(in_array) to check with post - idem voor max_items


    $now = time(); 
    $d = date("d", $now); $m = date("m",$now); $Y = date("Y",$now);

    $start = date("Y-m-d",mktime(0,0,0,$m,$d + $maxbefore,$Y));
    $cal = createCal($link, $maxitems,$start,$maxuntil,$prodid,$number);
            
    if(isset($_POST['r_from'])) {
        $until = createCal($link, $maxitems, $_POST['r_from'], $maxtime, $prodid, $number);
        $_SESSION['order']['from'] = $_POST['r_from'];
        unset($_SESSION['order']['until']);    
    }
    if(isset($_POST['r_until'])) {
                $_SESSION['order']['until'] = $_POST['r_until'];
                unset($_SESSION['order']['number']);
    } 
    
    if(isset($_POST['r_number'])) {
        $_SESSION['order']['number'] = $_POST['r_number'];
    }
    
    if(isset($_POST['submit'])) {
        $_SESSION['cart'][$prodid] = $_SESSION['order'];
        $_SESSION['cart'][$prodid]['name'] = $name;
        unset($_SESSION['order']);
        header("Location: cart.php");
        }
    
    if(isset($_POST['abort'])) {
        unset($_SESSION['order']);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>rezervejo</title>
        <?php        include_once 'inc.php'; ?>
        <link type="text/css" rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div id="header"><?= $header; ?></div>

        <img src="<?= $img;?>" alt="<?= $name; ?>" width="200" /><br />
        <p><?= $description; ?></p>
  <?php if($_SESSION['login'] != 'nologin') { ?>
        <div class="pick"><h3>Start date:</h3>
            <form name="start" method="post">
                <select onclick="this.form.submit()" name="r_from" size="15" class="lbwide" <?php if(isset($_SESSION['cart'][$prodid])) {
    echo 'disabled';
} ?>>
            <?php            
            
                foreach ($cal as $d) {
                echo '<option ';
                if(isset($_SESSION['order']['from']) && $_SESSION['order']['from'] == $d['date']){ echo 'selected';}
                if($d['open'] == 0 || $d['free'] == 0) { echo ' disabled';}
                echo ' value="' . $d['date'] . '">' . date("d-m",strtotime($d['date'])) . ' max (' . $d['free'] . ')'; 
                echo '</option>';
                }           
            ?>  
                </select>
            </form>
            <?php if(isset($_SESSION['cart'][$prodid])) {
    echo 'This product is already in your cart. To edit or remove, go to <a href="cart.php">your cart</a>.';
} ?>
        </div><?php 
            if(isset($_SESSION['order']['from'])) {
                $until = createCal($link, $maxitems, $_SESSION['order']['from'], $maxtime, $prodid, $number);
                ?>
        <div class="pick">
            
            <h3>End date:</h3>
            <form method="post">
                                <select onclick="this.form.submit()" name="r_until" size="15" class="lbwide">

                        <?php            
            
                foreach ($until as $d) {
                echo '<option ';
                if(isset($_SESSION['order']['until']) && $_SESSION['order']['until'] == $d['date']){ echo 'selected';}
                if($d['open'] == 0 || $d['free'] == 0) { echo ' disabled';}
                echo ' value="' . $d['date'] . '">' . date("d-m",strtotime($d['date'])) . ' (max ' . $d['free'] . ')'; 
                echo '</option>';
                }           
            ?>  
                </select>
            </form>
        
            </div><?php } ?>
        <?php if(isset($_SESSION['order']['until'])) {?>
        <div class="pick">
            
<h3>Number:</h3>
<form method="post">
<select name="r_number" size="15" class="lbwide" onclick="this.form.submit()">

 <?php
$d1 = $_SESSION['order']['from'];
$d2 = $_SESSION['order']['until'] - $_SESSION['order']['from'];
$maxaantal = createCal($link, $maxitems, $d1, $d2, $prodid, $number);
$mmm = $number;
foreach ($maxaantal as $ccc) {
    if($ccc['free'] < $mmm) {
        $mmm = $ccc['free'];
    }
} ?> 
<?php
for($i = 1; $i <= $mmm; $i++) {
?>
    <option value="<?= $i; ?>" <?php if(isset($_SESSION['order']['number'])  && $i == $_SESSION['order']['number']){echo 'selected';} ?>><?= $i;?></option>
<?php
}
 ?></select>
</form>  
        </div>
    <?php } ?> 
        <?php if(isset($_SESSION['order']['number'])) {?>
        <div class="pick">
            <h3>Confirm</h3>
        <form method="post">
            <input class="order" type="submit" name="submit" value="confirm" /><br />
            <input class="order" type="submit" name="abort" value="cancel" />
        </form>
        </div>
        <?php } ?>
  <?php } 
  elseif($_SESSION['login'] == 'nologin') {
      echo 'To reserve a product you need to be logged in';
  } ?>
    </body>
</html>