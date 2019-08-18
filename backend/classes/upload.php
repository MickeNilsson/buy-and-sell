<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './thumbnail-creator.php';
header('Content-Type: application/json');
$response_o = new stdClass();
$response_o->name = $_FILES['image']['name'];
$response_o->size = $_FILES['image']['size'];
move_uploaded_file($_FILES['image']['tmp_name'], '../images/fullsize/' . $_FILES['file']['name'] );
$srcImgPath_s = '../images/fullsize/' . $_FILES['file']['name'];
$destImgPath_s = '../images/thumbnails/' . $_FILES['file']['name'];
$destImgWidth_i = 200;
thumbnailCreator($srcImgPath_s, $destImgPath_s, $destImgWidth_i);
echo json_encode($response_o);

?>