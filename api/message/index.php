<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

$response_o = new stdClass();
$response_o->status = 'Request not processed';
switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $request_o = json_decode(file_get_contents('php://input'));
        require_once './send-message.php';
        require_once '../settings.php';
        require_once '../classes/db.php';
        $db_o = new DB($settings_aa);
        $ad_aa = $db_o->fetchAd($request_o->itemId);
        $mailWasSent_b = sendMessage($ad_aa['email'], $request_o->message);
        $response_o->status = $mailWasSent_b ? 'ok' : 'error';
        $response_o->message = $request_o->message;
        break;
}

echo json_encode($response_o, JSON_UNESCAPED_UNICODE);

?>