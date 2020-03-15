<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once '../../settings.php';
require_once '../../utilities/db.php';

$db_o = new DB($settings_aa);

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($db_o->fetchCounties(), JSON_UNESCAPED_UNICODE);
}