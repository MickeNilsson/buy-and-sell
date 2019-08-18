<?php

// Response headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Requires
require_once '../../classes/upload-image.php';

if(!empty($_FILES['image'])) {
    $result_b = uploadImage($_FILES['image']);
} else {
    $result_b = false;
}
$response_o = new stdClass();
$response_o->status = $result_b ? 'success' : 'error';
$response_s = json_encode($response_o, JSON_UNESCAPED_UNICODE);
echo $response_s;

?>
