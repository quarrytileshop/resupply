<?php
/**
 * resupply - Configuration File
 * Updated for new folder structure (May 14, 2026)
 * CLEAN PRODUCTION VERSION - no debug echoes
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
date_default_timezone_set('America/Los_Angeles');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}

// ======================
// DATABASE CONFIG (SECURE)
// ======================
$secure_config_path = __DIR__ . '/../../../resupply_db_config.php';

if (file_exists($secure_config_path)) {
    require_once $secure_config_path;
} else {
    die("Critical Error: Database configuration file not found.");
}

// ======================
// DATABASE CONNECTION (PDO)
// ======================
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ======================
// SITE SETTINGS + HELPERS
// ======================
define('SITE_NAME', 'Quarry Tile Shop Resupply');
define('BASE_URL', 'https://test.resupplyrocket.com/');
define('ASSETS_URL', BASE_URL . 'assets/');

define('ROLE_CUSTOMER', 'customer');
define('ROLE_ORG_ADMIN', 'org_admin');
define('ROLE_VENDOR', 'vendor');
define('ROLE_SUPER_ADMIN', 'super_admin');

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_super_admin() {
    return (!empty($_SESSION['is_admin'])) || (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_SUPER_ADMIN);
}

function is_vendor() {
    return (!empty($_SESSION['is_vendor_admin'])) || (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_VENDOR);
}

function is_org_admin() {
    return (!empty($_SESSION['is_organization_admin'])) || (isset($_SESSION['role']) && $_SESSION['role'] === ROLE_ORG_ADMIN);
}
?>