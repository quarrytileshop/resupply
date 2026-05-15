<?php
/**
 * resupply - Configuration File (UPDATED with Robust Usage Helpers)
 * Fixed column names to match your actual audit_logs table (timestamp + action_type)
 * Date: May 15, 2026
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
date_default_timezone_set('America/Los_Angeles');

// Secure session handling
if (session_status() === PHP_SESSION_NONE) {
    $sessionParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $sessionParams['lifetime'],
        'path'     => '/',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}

// ======================
// SECURE DATABASE CONFIG
// ======================
$secure_config_path = __DIR__ . '/../../../resupply_db_config.php';
if (file_exists($secure_config_path)) {
    require_once $secure_config_path;
} else {
    die("Critical Error: Database configuration file not found at $secure_config_path");
}

// ======================
// DATABASE CONNECTION
// ======================
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please contact support.");
}

// ======================
// SITE-WIDE CONSTANTS & HELPERS
// ======================
define('SITE_NAME', 'Resupply Rocket');
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

// CSRF protection
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ======================
// ROBUST BILLING & USAGE HELPERS (MATCHES YOUR LIVE DATABASE)
// ======================

function getBillableOrganizationsCount($vendor_id) {
    global $pdo;
    $currentMonth = date('Y-m');
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT organization_id) 
        FROM audit_logs 
        WHERE vendor_id = ? 
        AND DATE_FORMAT(timestamp, '%Y-%m') = ?
        AND organization_id IS NOT NULL
    ");
    $stmt->execute([$vendor_id, $currentMonth]);
    $activeOrgs = (int)$stmt->fetchColumn();
    return max(0, $activeOrgs - 2); // 2 free organizations per vendor
}

function hasActivityThisMonth($organization_id) {
    global $pdo;
    $currentMonth = date('Y-m');
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM audit_logs 
        WHERE organization_id = ? 
        AND DATE_FORMAT(timestamp, '%Y-%m') = ?
    ");
    $stmt->execute([$organization_id, $currentMonth]);
    return (int)$stmt->fetchColumn() > 0;
}

function logUsage($user_id, $organization_id, $vendor_id, $action) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO audit_logs (user_id, organization_id, vendor_id, action_type, details, timestamp)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$user_id, $organization_id, $vendor_id, $action, json_encode(['ip' => $_SERVER['REMOTE_ADDR']])]);
}
?>