<?php
// config.php – SECURE LOADER VERSION
// Modified: Tuesday, May 12, 2026 02:45 AM PDT
// This file no longer contains any credentials – they are loaded securely from outside public_html

require_once __DIR__ . '/../resupply_db_config.php';

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
