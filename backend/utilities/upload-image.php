<?php

/**
 * Uploads an image to default image folder
 * 
 * @param array $file_a Associative array containing data about a file
 * 
 * @return boolean true if it uploaded correctly, otherwise false
 */
function uploadImage($file_a, $id_i) {
    
    $fileExtension_s = substr($file_a['name'], strrpos($file_a['name'], '.'));
    $fileName_s = $id_i . $fileExtension_s;
    $fileTmpName_s = $file_a['tmp_name'];
    $uploadStatus_i = $file_a['error'];
    $fileSize_i = $file_a['size'];
    if($uploadStatus_i == UPLOAD_ERR_OK && $fileSize_i < 2000000){
        if(move_uploaded_file($fileTmpName_s, '../../../uploads/' . $fileName_s)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}