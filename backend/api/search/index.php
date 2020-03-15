<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once '../../settings.php';
require_once '../../utilities/db.php';

$db_o = new DB($settings_aa);

$searchArgs_o = json_decode(file_get_contents('php://input'));
$queryResult_s = $db_o->search($searchArgs_o);
$response_o = new stdClass();
$response_o->status = 'ok';
$response_o->queryResult = $queryResult_s;
echo json_encode($response_o, JSON_UNESCAPED_UNICODE);