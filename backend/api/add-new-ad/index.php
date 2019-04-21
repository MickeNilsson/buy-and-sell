<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
//header('Content-Type: application/json; charset=utf-8');

require_once '../../settings.php';
require_once '../../classes/db.php';
require_once '../../classes/validate.php';

$validate_o = new Validate();
$db_o = new DB($settings_a);

$newAd_o = json_decode(file_get_contents('php://input'));

$successfulValidation_m = $validate_o->validateAll($newAd_o);
if($successfulValidation_m) {
    $db_o->add($newAd_o);
    $success_o = new stdClass();
    $success_o->status = 'ok';
    echo json_encode($success_o, JSON_UNESCAPED_UNICODE);
} else {
    $error_o = new stdClass();
    $error_o->status = 'error';
    $error_o->description = $newAd_o;
    echo json_encode($error_o, JSON_UNESCAPED_UNICODE);
}

?>