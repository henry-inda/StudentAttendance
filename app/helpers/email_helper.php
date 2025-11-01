<?php
// Include Composer's autoloader
require_once 'vendor/autoload.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_email($to, $subject, $body, $template = null) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Removed verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = SMTP_HOST;                              //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = SMTP_USER;                              //SMTP username
        $mail->Password   = SMTP_PASS;                              //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = SMTP_PORT;                              //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom(SMTP_USER, SYSTEM_NAME); // Assuming SYSTEM_NAME is defined in config.php
        $mail->addAddress($to);                                     //Add a recipient

        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; // Optional

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
