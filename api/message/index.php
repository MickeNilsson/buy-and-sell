<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require_once '../classes/phpmailer.php';
require_once '../classes/smtp.php';
echo 'tst';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
$mail->isSMTP();                                            // Send using SMTP
$mail->Host = 'send.one.com'; 
$mail->Port = 465;
$mail->Username   = 'mikaelnilsson1973@hotmail.com';                     // SMTP username
$mail->Password   = 'digi4146';
$mail->setFrom('info@digizone.se', 'Mailer');
$mail->addAddress('mikael@digizone.se', 'Mikael');
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->send();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_o = json_decode(file_get_contents('php://input'));
    echo json_encode($message_o, JSON_UNESCAPED_UNICODE);
}

?>