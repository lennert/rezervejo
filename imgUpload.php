<?php
function imageUpload($name,$uploaddir,$file,$maxsize) {
    $allowed = array('image/jpg','image/jpeg','image/png','image/gif');
    $filext = $_FILES[$name]['type'];
    $uploadedfile = $uploaddir . '' . $file;
    $quoi = array();
    if($_FILES[$name]['size'] > $maxsize ) {
        $quoi['message'] = 'File Too Big'; 
        $quoi['succes'] = 0; 
    }
    elseif(!in_array($filext, $allowed)) {        
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