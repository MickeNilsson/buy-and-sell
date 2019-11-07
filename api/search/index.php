<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// // Allow from any origin
// if (isset($_SERVER['HTTP_ORIGIN'])) {
//     // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
//     // whitelist of safe domains
//     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//     header('Access-Control-Allow-Credentials: true');
//     header('Access-Control-Max-Age: 86400');    // cache for 1 day
// }
// // Access-Control headers are received during OPTIONS requests
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

//     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//         header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

//     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//         header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

// }

require_once '../settings.php';
require_once '../classes/db.php';

$db_o = new DB($settings_a);

$searchArgs_o = json_decode(file_get_contents('php://input'));
//print_r($db_o->search($search_o));exit;
$queryResult_s = $db_o->search($searchArgs_o);
$response_o = new stdClass();
$response_o->status = 'ok';
$response_o->queryResult = $queryResult_s;
echo json_encode($response_o, JSON_UNESCAPED_UNICODE);




?>