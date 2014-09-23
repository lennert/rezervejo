<?php
function imageUpload($name,$uploaddir,$file,$maxsize) {
    $allowed = array('jpg','jpeg','png','gif');
    $filext = pathinfo($_FILES[$name]['tmp_name'],PATHINFO_EXTENSION);
    $uploadedfile = $uploaddir . '' . $file;
    $quoi = array();
    if($_FILES[$name]['size'] > $maxsize ) {
        $quoi['message'] = 'File Too Big'; 
        $quoi['succes'] = 0; 
    }
    if(!in_array($filext, $allowed)) {        
        $quoi['message'] = 'Not an allowed file. Only jpg, png or gif allowed'; 
        $quoi['succes'] = 0;}
    else { 
        if(move_uploaded_file($_FILES[$name]['tmp_name'], $uploadedfile)) {
        $quoi['message'] = 'File Upload succeeded' ; 
        $quoi['succes'] = 1;
        }  
        else {
        $quoi['message'] = 'File Upload Failed'; 
        $quoi['succes'] = 0;
        }
    }   
    return $quoi;    
}