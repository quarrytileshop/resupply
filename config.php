<?php
// config.php – Modified 2025-03-10 21:00 – Lines: 10

$dsn = 'mysql:host=localhost;dbname=resupply_test';
$db_username = 'russelltest';          // Your cPanel database username
$db_password = '60@60Resupplyrocket';  // Your cPanel database password

try {
    $pdo = new PDO($dsn, $db_username, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    error_log("PDO Connection failed: " . $e->getMessage());
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>
