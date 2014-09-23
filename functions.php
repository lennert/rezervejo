<?php
function createCal($link, $maxitems,$startdate,$maxuntil,$prodid,$number) {
$d = date("d",strtotime($startdate)); $m = date("m",strtotime($startdate)); $Y = date("Y",strtotime($startdate));
$date1 = date("d-m-Y",mktime(0,0,0,$m,$d,$Y));
$date2 = date("d-m-Y",mktime(0,0,0,$m,$d + $maxuntil ,$Y));                 
$start = strtotime($date1); $stop = strtotime($date2);
     while ($start <= $stop) {
        $dn  = date("w",$start); $key = date("Y-m-d",$start); 
        $d = mysqli_query($link,"SELECT * FROM days where day_nr = '$dn'");
        $sfd = mysqli_fetch_assoc($d); $dnr = $sfd['open']; if(empty($dnr)) { $dnr = 0;}
        $d_s = mysqli_query($link, "SELECT * FROM days_special WHERE day = '$key'");
        if(mysqli_num_rows($d_s) == 1) {
        $d_s_d = mysqli_fetch_assoc($d_s);
        if($d_s_d['open'] == 0) {$dnr = 0;} elseif($d_s_d['open'] == 1) {$dnr = 1;} }
        $prodaant = mysqli_query($link,"SELECT * FROM reservations WHERE `from` <= '$key' AND `until` >= '$key' AND `product` = '$prodid'");
        $fffff = 0;
        while($aantal = mysqli_fetch_assoc($prodaant)) { $fffff  += $aantal['number']; }
        $nm_free = $number - $fffff;  if($nm_free < $maxitems) {$max = $nm_free;} else {$max = $maxitems;}
        $dates[$key] = array('date' => date('d-m-Y',$start),'open' => $dnr, 'free' => $max); 
        $start = strtotime('+1 days',$start); 
    }
    return $dates;
}