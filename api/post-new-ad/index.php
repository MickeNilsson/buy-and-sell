<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Allow-Headers: Content-Type');
//header('Content-Type: application/json; charset=utf-8');

require_once '../settings.php';
require_once '../classes/db.php';

//print_r($_POST);print_r($_FILES['image']);exit;

$db_o = new DB($settings_a);
$params_aa = $db_o->add($_POST);
echo json_encode($params_aa, JSON_UNESCAPED_UNICODE);

exit;



echo json_encode($newAd_o, JSON_UNESCAPED_UNICODE);exit;
print_r($newAd_o);exit;
echo file_get_contents('php://input');exit;
print_r($_POST);exit;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
//header('Content-Type: application/json; charset=utf-8');

require_once '../settings.php';
require_once '../classes/db.php';
//require_once '../../classes/validate.php';

//$validate_o = new Validate();
$db_o = new DB($settings_a);
print_r($_POST); exit;
$newAd_aa = json_decode(file_get_contents('php://input'), true);
echo json_encode($newAd_aa, JSON_UNESCAPED_UNICODE);exit;
// print_r($newAd_aa);exit;
$id_i = $db_o->postNewAd($newAd_aa);
$response_o = new stdClass();
$response_o->success = $id_i;
echo json_encode($response_o, JSON_UNESCAPED_UNICODE);

// $successfulValidation_b = $validate_o->validateAll($newAd_o);
// if($successfulValidation_b) {
//     $db_o->add($newAd_o);
//     $success_o = new stdClass();
//     $success_o->status = 'ok';
//     echo json_encode($success_o, JSON_UNESCAPED_UNICODE);
// } else {
//     $error_o = new stdClass();
//     $error_o->status = 'error';
//     $error_o->description = $newAd_o;
//     echo json_encode($error_o, JSON_UNESCAPED_UNICODE);
// }

?>