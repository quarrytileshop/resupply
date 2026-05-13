<?php
// config.php – SECURE LOADER VERSION (FINAL)
// Modified: Tuesday, May 12, 2026 03:40 AM PDT
// Credentials are now safely loaded from outside public_html

$secure_config_path = __DIR__ . '/../../resupply_db_config.php';

if (file_exists($secure_config_path)) {
    require_once $secure_config_path;
} else {
    error_log("CRITICAL: resupply_db_config.php not found in account root");
    die("Configuration error. Please contact support.");
}

$dsn = "mysql:host={$secure_db_host};dbname={$secure_db_name};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $secure_db_username, $secure_db_password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please contact support.");
}
?>
