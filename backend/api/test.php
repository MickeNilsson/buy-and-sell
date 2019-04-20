<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../settings.php';
require_once '../classes/db.php';

$db_o = new DB($settings_a);
$args_am = [
    'first' => 1
];
$db_o->add($args_am);

?>