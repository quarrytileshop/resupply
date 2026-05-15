<?php
/**
 * resupply - Logout Page (Professional Rewrite)
 * Secure logout with session destruction
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

if (is_logged_in()) {
    // Audit log before destroying session
    $log = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, 'logout', ?)");
    $log->execute([$_SESSION['user_id'], json_encode(['ip' => $_SERVER['REMOTE_ADDR']])]);
}

session_destroy();
header("Location: " . BASE_URL . "login.php");
exit;
?>