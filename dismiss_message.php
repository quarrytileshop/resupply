<?php
/**
 * resupply - Dismiss Message Handler
 * Updated for new folder structure (May 14, 2026)
 * Simple AJAX/session message dismiss - no includes needed beyond config
 */

require_once 'includes/config.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

// This file is usually called via AJAX or GET to clear a temporary message
if (isset($_GET['dismiss']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear any session message
    if (isset($_SESSION['message'])) {
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        unset($_SESSION['error']);
    }

    // If called via AJAX, just return success
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['status' => 'success']);
        exit;
    }

    // Otherwise redirect back to the referring page
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header("Location: $redirect");
    exit;
}

// Fallback if someone visits directly
header("Location: dashboard.php");
exit;
?>