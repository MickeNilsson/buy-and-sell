<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once '../../settings.php';
require_once '../../classes/db.php';

$db_o = new DB($settings_a);

$search_o = json_decode(file_get_contents('php://input'));




?>