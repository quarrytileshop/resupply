<?php
/**
 * resupply - Index / Landing Page (Professional Rewrite)
 * Simple redirect to login or dashboard
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

if (is_logged_in()) {
    if (is_super_admin()) {
        header("Location: " . BASE_URL . "admin/admin_dashboard.php");
    } elseif (is_vendor()) {
        header("Location: " . BASE_URL . "vendor/vendor_dashboard.php");
    } elseif (is_org_admin()) {
        header("Location: " . BASE_URL . "organization_admin.php");
    } else {
        header("Location: " . BASE_URL . "dashboard.php");
    }
    exit;
} else {
    header("Location: " . BASE_URL . "login.php");
    exit;
}
?>