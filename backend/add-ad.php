<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once './validate.php';
require_once './pdo.php';



$data_om = json_decode(file_get_contents('php://input'));
//$validationErrors_as = validate($data_am);


// print_r($validationErrors_as);
// print_r($data_am);
// sleep(2);

echo '{"status":"ok","ad":"' . $data_om->{'ad'} . '","county":' . $data_om->{'county'} . '}';
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