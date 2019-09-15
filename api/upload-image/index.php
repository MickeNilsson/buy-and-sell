<?php

// Response headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
//header('Content-Type: application/json; charset=utf-8');

// Requires
//require_once '../../classes/upload-image.php';

if(!empty($_FILES['image'])) {
    $result_b = uploadImage($_FILES['image']);
} else {
    $result_b = false;
}

$response_o = new stdClass();
if($result_b == true) {
    $response_o->status = 'success';
} else {
    $response_o->status = 'error';
}
//$result_b ? 'success' : 'error';
$response_s = json_encode($response_o, JSON_UNESCAPED_UNICODE);
echo $response_s;

/**
 * Uploads an image to default image folder
 * 
 * @param array $file_a Associative array containing data about a file
 * 
 * @return boolean true if it uploaded correctly, otherwise false
 */
function uploadImage($file_a) {
    $fileName_s = $file_a['name'];
    $fileTmpName_s = $file_a['tmp_name'];
    $uploadStatus_i = $file_a['error'];
    $fileSize_i = $file_a['size'];
    if($uploadStatus_i == UPLOAD_ERR_OK && $fileSize_i < 10000000){
        if(move_uploaded_file($fileTmpName_s, '../../uploads/' . $fileName_s)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>
