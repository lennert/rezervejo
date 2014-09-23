<?php session_start();
include_once 'logincheck.inc';
if(!isset($_GET['product'])) { header('Location: index.php'); }
$prodid = $_GET['product'];
print_r($_SESSION);
/* 
 * 1. Kijken naar rechten: voor maxitems, maxperiode, ... op basis daarvan
 *      kalender genereren.
 * 2. jquery >  maxdatums selecteren : bv max 7 dagen > max selectie
 *              maxitems genereren : overal tellen, minste cijfer tonen
 * 3. form continue > add_to_cart
 */
        $prods = mysqli_query($link, "SELECT * FROM product WHERE id = $prodid");
        while($allprods = mysqli_fetch_assoc($prods)) {
            foreach ($allprods as $key => $value) {${$key} = $value;}    
        } 
include_once 'rights.inc'; 

if($_SESSION['login'] == 'nologin') {
    $formshow = 'disabled';
     $maxbefore = 0;
     $maxuntil = 0;
     $maxtime = 0;
     $maxitems = 0;
}
elseif($_SESSION['login'] == 'admin') {
     $maxbefore = 0;
     $maxuntil = 31;
     $maxtime = 31;
     $maxitems = $number;
    }

if(isset($_POST['cart'])) {
    foreach($_POST as $key => $value) {${$key} = $value;}
    $_SESSION['cart'][$prodid] = array('from' => $from , 'until' => $until , 'number' => $number);
}    
    function dates_range($date1, $date2) { 
        if ($date1<$date2) { 
            $dates_range[]=$date1; 
            $date1=strtotime($date1); 
            $date2=strtotime($date2); 
            while ($date1!= $date2) { 
                $date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1)); 
                $dates_range[]=date('d-m-Y', $date1); 
            } 
        } 
        return $dates_range; 
    }           
?>
<div id="header"><?= $header; ?></div>
<?php

echo '<p>' . $name . ' ' . $description . '</p>';

echo '<p>' . $maxitems . ' ' . $maxbefore . ' ' . $maxuntil . ' ' . $maxtime . '</p>';
//DATUMS KLAARZETTEN:
$d = date("d",time()); $m = date("m",time()); $Y = date("Y",time());
$date1 = date("d-m-Y",mktime(0,0,0,$m,$d + $maxbefore ,$Y));
$date2 = date("d-m-Y",mktime(0,0,0,$m,$d + $maxuntil ,$Y));

$start = strtotime($date1);
$stop = strtotime($date2);
  while ($start <= $stop) {
        $dn  = date("w",$start); $key = date("Y-m-d",$start); 
        $days = mysqli_query($link,"SELECT * FROM days where day_nr = '$dn'");
        $sfd = mysqli_fetch_assoc($days); $dnr = $sfd['open']; if(empty($dnr)) { $dnr = 0;}
        $days_special = mysqli_query($link, "SELECT * FROM days_special WHERE day = '$key'");
        if(mysqli_num_rows($days_special) == 1) {
        $d_s_d = mysqli_fetch_assoc($days_special);
        if($d_s_d['open'] == 0) {$dnr = 0;} elseif($d_s_d['open'] == 1) {$dnr = 1;} }
        $prodaant = mysqli_query($link,"SELECT * FROM reservations WHERE `from` <= '$key' AND `until` >= '$key' AND `product` = '$prodid'");
        $aantal = mysqli_fetch_assoc($prodaant);
        $nm_free = $number - $aantal['number'];  if($nm_free < $maxitems) {$max = $nm_free;} else {$max = $maxitems;}
        $dates[$key] = array('date' => date('d-m-Y',$start),'open' => $dnr, 'free' => $max); 
        $start = strtotime('+1 days',$start); 
    }
foreach($dates as $date) {
    echo '<div style="day">';
   echo $date['date'] . ' ' . $date['free'] . ' ' . $date['open'];
   echo '</div>';
}

//while($nrmldays = mysqli_fetch_assoc($normal)) {print_r($nrmldays) ; }
//while($clsngdays = mysqli_fetch_assoc($closing)) {print_r($clsngdays);}


// ALLES uit tables te halen: in 1 array: array( 'day' => '2' , 'date' => '2014-09-09' , 'open' => '1' , 'available' => '10')
// available toont ook max dat mag bv bij studenten: 1