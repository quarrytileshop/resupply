<?php
// config.php – FIXED FOR SUBDOMAIN FOLDER STRUCTURE
// Modified: Tuesday, May 12, 2026 03:25 AM PDT
// This file no longer contains any credentials

error_reporting(E_ALL);
ini_set('display_errors', 1);   // Temporary debug mode – we will turn this off later

echo "<h2>🔧 Config Debug Mode</h2>";

$secure_config_path = __DIR__ . '/../../resupply_db_config.php';

if (file_exists($secure_config_path)) {
    echo "<p style='color:green'>✅ Found secure config file at correct location.</p>";
    require_once $secure_config_path;
} else {
    echo "<p style='color:red'>❌ Could NOT find resupply_db_config.php in account root.</p>";
    echo "<p>Please double-check that the file is in the top-level folder (the one that contains the <strong>public_html</strong> folder).</p>";
    die();
}

$dsn = "mysql:host={$secure_db_host};dbname={$secure_db_name};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $secure_db_username, $secure_db_password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    echo "<p style='color:green'>✅ Database connection successful!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    die();
}

// Everything is good – the rest of your site will now load normally
?>
