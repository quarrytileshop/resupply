<?php
/**
 * resupply - Email Functions
 * Updated for new folder structure (May 14, 2026)
 * PHPMailer path updated to ../vendor/
 */

require_once 'config.php';
require_once '../vendor/PHPMailer/src/Exception.php';
require_once '../vendor/PHPMailer/src/PHPMailer.php';
require_once '../vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send Purchase Order (PO) email to vendor
 */
function send_po_email($vendor_email, $vendor_name, $subject, $html_body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings (loaded from secure config.php)
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($vendor_email, $vendor_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;
        $mail->AltBody = strip_tags($html_body);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("PO Email failed: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Generic email sending function (used by other parts of the app)
 */
function send_email($to, $subject, $body, $is_html = true) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($to);

        $mail->isHTML($is_html);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if (!$is_html) {
            $mail->AltBody = $body;
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("General email failed: " . $e->getMessage());
        return false;
    }
}

?>