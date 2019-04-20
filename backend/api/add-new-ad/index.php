<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
//header('Content-Type: application/json; charset=utf-8');

//require_once './validate.php';
require_once '../../settings.php';
require_once '../../classes/db.php';

$data_o = json_decode(file_get_contents('php://input'));

print_r($data_o);
$db_o = new DB($settings_a);
echo $db_o->add($data_o);

// $bytes = random_bytes(5);
// var_dump(bin2hex($bytes));
//$validationErrors_as = validate($data_am);


// print_r($validationErrors_as);
// print_r($data_am);
// sleep(2);

//echo '{"status":"ok","ad":"' . $data_o->ad . '","county":' . $data_o->county . '}';
// echo json_encode($data, JSON_UNESCAPED_UNICODE);


// ad: "En bok av Strindberg. En gigant, bland giganter."
// category: 8
// county: 14
// email: "mikaelnilsson1973@hotmail.com"
// heading: "Röda rummet"
// image: undefined
// phone: "730725069"
// price: 123












?>