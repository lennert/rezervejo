<?php session_start();
$_SESSION['user'] = 'lennho';
$_SESSION['login'] = 'admin';
//$_SESSION['admin'] = true;
$_SESSION['cart'] = array(
    'product x' =>
    array('aantal' => '9' , 'date start' => '2014-09-02' , 'date stop' => '2014-09-08'),
    'product y' =>
    array('aantal' => '9' , 'date start' => '2014-09-02' , 'date stop' => '2014-09-08')
);

$z = array('aantal' => '9' , 'date start' => '2014-09-02' , 'date stop' => '2014-09-08');
$_SESSION['cart']['product z'] = $z;


//unset($_SESSION['cart']['product x']);

$z2 = array('aantal' => 'totaal andere waarde' , 'date start' => '2014-09-02' , 'date stop' => '2014-09-08');
$_SESSION['cart']['product z'] = $z2;

//$q = array('aantal' => '42' , 'date start' => 'sss' , 'date stop' => 'zzz');
//$_SESSION['cart']['product id 8'] = $q;


//unset($_SESSION['cart']);

header('Location:index.php');