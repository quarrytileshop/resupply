<?php
// email_functions.php - Modification Date: August 08, 2025, 12:00 PM PDT - Total Lines: 50
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
require_once 'config.php'; // Load email password
function send_email($to, $subject, $html_body, $plain_body) {
    global $email_password; // From config.php
    $mail = new PHPMailer(true);
    try {
        // Enable verbose debug output
        $mail->SMTPDebug = 4; // Detailed DKIM and SMTP logs
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer [$level]: $str", 3, 'email_debug.log');
        };
        // SMTP settings for GoDaddy
        $mail->isSMTP();
        $mail->Host = 'mail.resupplyrocket.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'orders@resupplyrocket.com';
        $mail->Password = $email_password; // From config.php
        $mail->SMTPSecure = 'ssl';
        $mail->Timeout = 5; // Fast timeout to prevent hanging
        // Sender and recipients
        $mail->setFrom('orders@resupplyrocket.com', 'Resupply Rocket');
        $mail->Sender = 'orders@resupplyrocket.com'; // For bounce handling
        $mail->addReplyTo('orders@resupplyrocket.com', 'Resupply Rocket Support');
        $to_list = explode(',', $to);
        foreach ($to_list as $recipient) {
            $recipient = trim($recipient);
            if (!empty($recipient)) {
                $mail->addAddress($recipient);
            }
        }
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html_body;
        $mail->AltBody = $plain_body;
        // DKIM settings (GoDaddy handles signing server-side)
        $mail->DKIM_domain = 'resupplyrocket.com';
        $mail->DKIM_selector = 'default';
        $mail->send();
        error_log("Email sent successfully to $to", 3, 'email_debug.log');
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer failed: " . $mail->ErrorInfo, 3, 'email_debug.log');
        // Fallback to PHP mail()
        $headers = "From: Resupply Rocket <orders@resupplyrocket.com>\r\n";
        $headers .= "Reply-To: Resupply Rocket Support <orders@resupplyrocket.com>\r\n";
        $headers .= "Return-Path: orders@resupplyrocket.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        if (mail($to, $subject, $html_body, $headers)) {
            error_log("Fallback mail() sent successfully to $to", 3, 'email_debug.log');
            return true;
        } else {
            error_log("Fallback mail() failed for $to", 3, 'email_debug.log');
            return false;
        }
    }
}
?>
