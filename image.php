<?php
        header("Content-type: image/png");
$img = imagecreatetruecolor(256, 256)
    or die("Error while creating the graphic.");
$white = imagecolorallocate($img, 255, 255, 255);
 
for ($x=0; $x<256; $x++)
{
    for ($y=0; $y<256; $y++)
    {
        if (mt_rand(0,1) === 1)
        {
             imagesetpixel($img, $x, $y, $white);
         }
     }
 }
 imagepng($img);
 imagedestroy($img);
?>