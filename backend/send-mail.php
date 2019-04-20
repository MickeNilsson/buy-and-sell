<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * Send mail.
 * 
 * @param string $recipient The email address of the recipient of the email.
 * @param string $html The HTML mail.
 * 
 * @return string[] An array with the following elements:
 *                  If everything went ok:
 *                  status => "ok"
 *                  If an error arose:
 *                  status => "error"
 *                  description => "The error message." 
 */
function sendMail($rescipient, $html) {
    require_once './libs/php-mailer/Exception.php';
    require_once './libs/php-mailer/OAuth.php';
    require_once './libs/php-mailer/PHPMailer.php';
    require_once './libs/php-mailer/POP3.php';
    require_once './libs/php-mailer/SMTP.php';
    
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'send.one.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@digizone.se';                 // SMTP username
        $mail->Password = 'gwailo';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom('noreply@digizone.se', 'Mailer');
        $mail->addAddress('mikaelnilsson1973@hotmail.com', 'Mikael Nilsson');     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
    
        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        $response = array(
            'status' => 'ok'
        );
        return $response;
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'description' => $mail->ErrorInfo
        );
        return $response;
    }
}
