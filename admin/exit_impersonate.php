<?php
/**
 * resupply - Exit Impersonate (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and redirects updated
 */

require_once '../includes/config.php';

if (!isset($_SESSION['impersonating']) || !isset($_SESSION['original_user_id'])) {
    // Not currently impersonating - just go back to dashboard
    header("Location: ../dashboard.php");
    exit;
}

// Restore the original admin session
$_SESSION['user_id']   = $_SESSION['original_user_id'];
$_SESSION['username']  = $_SESSION['original_username'] ?? '';
$_SESSION['first_name'] = $_SESSION['original_first_name'] ?? 'Admin'; // fallback
$_SESSION['role']      = $_SESSION['original_role'] ?? 'super_admin';

// Clean up impersonation flags
unset(
    $_SESSION['impersonating'],
    $_SESSION['original_user_id'],
    $_SESSION['original_username'],
    $_SESSION['original_role'],
    $_SESSION['original_first_name']
);

$_SESSION['message'] = "You have stopped impersonating and are back as the super admin.";

// Redirect to admin dashboard
header("Location: admin_dashboard.php");
exit;
?>