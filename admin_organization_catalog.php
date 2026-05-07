<?php
// admin_impersonate.php - Modification Date: August 18, 2025, 12:00 PM PDT - Total Lines: 60
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target_user_id'])) {
    $target_user_id = intval($_POST['target_user_id']);
    // Verify target user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->execute(['id' => $target_user_id]);
    if ($stmt->fetch()) {
        // Log the impersonation start
        $admin_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("INSERT INTO impersonation_logs (admin_id, user_id, start_time) VALUES (:admin, :user, NOW())");
        $stmt->execute(['admin' => $admin_id, 'user' => $target_user_id]);
        $log_id = $pdo->lastInsertId();
        // Set impersonation session
        $_SESSION['impersonating'] = true;
        $_SESSION['original_user_id'] = $admin_id;
        $_SESSION['user_id'] = $target_user_id;
        $_SESSION['impersonation_log_id'] = $log_id;
        // Redirect to dashboard or landing page
        header("Location: dashboard.php"); // Or order.php if propane-focused
        exit;
    } else {
        header("Location: admin_dashboard.php?error=User not found.");
        exit;
    }
} else {
    header("Location: admin_dashboard.php?error=Invalid request.");
    exit;
}
?>
