<?php
/**
 * resupply - Configuration File
 * Updated for new folder structure (May 13, 2026)
 */

// ======================
// SECURITY & ENVIRONMENT
// ======================
error_reporting(E_ALL);
ini_set('display_errors', 0);           // Change to 1 only when debugging
date_default_timezone_set('America/Los_Angeles');

// ======================
// SESSION & SECURITY
// ======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent session fixation
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}

// ======================
// DATABASE CONFIG (SECURE)
// ======================
// GoDaddy shared hosting - config file is kept OUTSIDE public_html
$secure_config_path = __DIR__ . '/../../../resupply_db_config.php';

if (file_exists($secure_config_path)) {
    require_once $secure_config_path;
} else {
    die("Critical Error: Database configuration file not found. Please contact the administrator.");
}

// ======================
// SITE SETTINGS
// ======================
define('SITE_NAME', 'Quarry Tile Shop Resupply');
define('BASE_URL', 'https://yourdomain.com/resupply/'); // ← CHANGE THIS if your site is in a subfolder
define('ASSETS_URL', BASE_URL . 'assets/');

// ======================
// ROLE CONSTANTS
// ======================
define('ROLE_CUSTOMER', 'customer');
define('ROLE_ORG_ADMIN', 'org_admin');
define('ROLE_VENDOR', 'vendor');
define('ROLE_SUPER_ADMIN', 'super_admin');

// ======================
// UPLOAD & PATH SETTINGS
// ======================
define('PRODUCT_IMAGE_PATH', __DIR__ . '/../assets/product-images/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// ======================
// EMAIL SETTINGS
// ======================
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('FROM_NAME', 'Quarry Tile Shop Resupply');

// ======================
// HELPER FUNCTIONS
// ======================
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_super_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_SUPER_ADMIN;
}

function is_vendor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_VENDOR;
}

function is_org_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ORG_ADMIN;
}

?>