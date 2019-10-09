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
$args_aa = array(
    'body'     => empty($_POST['body']) ? '' : $_POST['body'],
    'category' => empty($_POST['category']) ? '' : $_POST['category'],
    'county'   => empty($_POST['county']) ? '' : $_POST['county'],
    'email'    => empty($_POST['email']) ? '' : $_POST['email'],
    'header'   => empty($_POST['header']) ? '' : $_POST['header'],
    'price'    => empty($_POST['price']) ? '' : $_POST['price'],
    'type'     => empty($_POST['type']) ? '' : $_POST['type']
);
$result_aa = $db_o->add($args_aa);
if($result_aa['status'] === 'success' && !empty($_FILES['image'])) {
    $result_b = uploadImage($_FILES['image'], $result_aa['lastInsertId']);
    $result_aa['fileUpload'] = $result_b;
}
echo json_encode($result_aa, JSON_UNESCAPED_UNICODE);

?>