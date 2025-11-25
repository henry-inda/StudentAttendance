<?php
// Include Composer's autoloader
require_once 'vendor/autoload.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Optional: include logger helper if available. Only include if APP constant is defined
// to avoid bootstrap dependency issues in quick-run scripts.
if (defined('APP') && file_exists(APP . '/helpers/logger.php')) {
    require_once APP . '/helpers/logger.php';
}

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
        $mail->setFrom(SMTP_USER, defined('SYSTEM_NAME') ? SYSTEM_NAME : SMTP_USER);
        $mail->addAddress($to);                                     //Add a recipient

        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        // Get a message id when available
        $messageId = method_exists($mail, 'getLastMessageID') ? $mail->getLastMessageID() : null;

        // Prepare log details
        $detail = [
            'to' => $to,
            'subject' => $subject,
            'message_id' => $messageId,
            'status' => 'sent'
        ];

        // Try to write to system log (DB) if helper is available
        try {
            if (function_exists('log_activity')) {
                // attempt to attach user id if available
                $user_id = (function_exists('get_session') ? get_session('user_id') : ($_SESSION['user_id'] ?? null)) ?? 0;
                log_activity($user_id, 'email_sent', $detail);
            }
        } catch (Exception $e) {
            // ignore DB logging failures, will fallback to file log
        }

        // Also write to a file-based log (fallback and quick trace)
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logLine = date('c') . ' | SENT | ' . json_encode($detail) . PHP_EOL;
        @file_put_contents($logDir . '/email.log', $logLine, FILE_APPEND);

        return true;
    } catch (Exception $e) {
        $errorInfo = $mail->ErrorInfo ?? $e->getMessage();

        // Prepare failure details
        $detail = [
            'to' => $to,
            'subject' => $subject,
            'error' => $errorInfo,
            'status' => 'failed'
        ];

        // DB log attempt
        try {
            if (function_exists('log_activity')) {
                $user_id = (function_exists('get_session') ? get_session('user_id') : ($_SESSION['user_id'] ?? null)) ?? 0;
                log_activity($user_id, 'email_failed', $detail);
            }
        } catch (Exception $ex) {
            // ignore
        }

        // File log
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        $logLine = date('c') . ' | FAILED | ' . json_encode($detail) . PHP_EOL;
        @file_put_contents($logDir . '/email.log', $logLine, FILE_APPEND);

        error_log("Message could not be sent. Mailer Error: {$errorInfo}");
        return false;
    }
}
