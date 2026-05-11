<?php
// config.php – Modified 2026-05-08 – Final Version
$dsn = 'mysql:host=localhost;dbname=resupply_test';
$db_username = 'russelltest';
$db_password = '60@60Resupplyrocket';

try {
    $pdo = new PDO($dsn, $db_username, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please contact support.");
}
?>
