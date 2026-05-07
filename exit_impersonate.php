<?php
// exit_impersonate.php - Modification Date: August 18, 2025, 12:00 PM PDT - Total Lines: 40
require_once 'config.php';
session_start();
if (isset($_SESSION['impersonating']) && $_SESSION['impersonating']) {
    // Log end time
    $log_id = $_SESSION['impersonation_log_id'] ?? 0;
    if ($log_id > 0) {
        $stmt = $pdo->prepare("UPDATE impersonation_logs SET end_time = NOW() WHERE id = :id");
        $stmt->execute(['id' => $log_id]);
    }
    // Restore original session
    $_SESSION['user_id'] = $_SESSION['original_user_id'];
    unset($_SESSION['impersonating']);
    unset($_SESSION['original_user_id']);
    unset($_SESSION['impersonation_log_id']);
    header("Location: admin_dashboard.php?message=Exited impersonation.");
    exit;
} else {
    header("Location: dashboard.php");
    exit;
}
?>
