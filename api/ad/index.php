<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once '../settings.php';
require_once '../classes/db.php';
require_once '../classes/upload-image.php';

$db_o = new DB($settings_aa); 
if(empty($_FILES['image'])) {
    $fileExtension_s = 'no image';
} else {
    $fileExtension_s = substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], '.') + 1);
}
$args_aa = array(
    'body'      => empty($_POST['body']) ? '' : $_POST['body'],
    'category'  => empty($_POST['category']) ? '' : $_POST['category'],
    'county'    => empty($_POST['county']) ? '' : $_POST['county'],
    'email'     => empty($_POST['email']) ? '' : $_POST['email'],
    'header'    => empty($_POST['header']) ? '' : $_POST['header'],
    'image'     => $fileExtension_s,
    'price'     => empty($_POST['price']) ? '-1' : $_POST['price'],
    'published' => date('Y-m-d'),
    'type'      => empty($_POST['type']) ? '' : $_POST['type'],
    'uuid'      => uniqid()
);
$result_aa = $db_o->add($args_aa);
if($result_aa['status'] === 'success' && !empty($_FILES['image'])) {
    $result_b = uploadImage($_FILES['image'], $result_aa['id']);
    $result_aa['fileUpload'] = $result_b;
}

$message_s ='<html><head>Ny annons</head><body>'
    . '<h3>Ny annons på buyandsell.se</h3>'
    . '<div><p>Du hittar din annons <a target="_blank" href="http://www.digizone.se/buy-and-sell/?id=' . $result_aa['id'] . '">här</a>.</p>'
    . '<p>Tack för att du använder dig av Buy and Sell!</p>'
    . '</div></body></html>';
// To send HTML mail, the Content-type header must be set
//$headers[] = 'MIME-Version: 1.0';
$headers_s = 'Content-Type: text/html; charset=utf-8' . "\r\n"
           . 'From: Buy and Sell <info@digizone.se>';
$success_b = mail($args_aa['email'], 'Ny annons på Buy and Sell',  $message_s, $headers_s);
echo json_encode($result_aa, JSON_UNESCAPED_UNICODE);

?>