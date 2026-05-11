<?php
// admin_catalog.php – DEBUG + SAFE VERSION – Modified 2026-05-08
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

echo "<h3>DEBUG: admin_catalog.php is running</h3>";

// Show the actual column names in the table
echo "<h4>Columns in catalog_items table:</h4>";
$stmt = $pdo->query("SHOW COLUMNS FROM catalog_items");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "<pre>" . print_r($columns, true) . "</pre>";

// Use a safe column for ordering (we'll use the first available column)
$safe_column = $columns[0] ?? 'id';

try {
    $stmt = $pdo->query("SELECT * FROM catalog_items ORDER BY " . $safe_column . " LIMIT 50");
    $items = $stmt->fetchAll();
    echo "<p>✅ Query successful. Found " . count($items) . " items (sorted by " . $safe_column . ")</p>";
} catch (Exception $e) {
    echo "<div style='color:red;background:#ffe6e6;padding:15px;border:2px solid red;'>Database Error: " . $e->getMessage() . "</div>";
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Catalog Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <div class="navbar-nav">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                <a class="nav-link active" href="admin_catalog.php">Catalog</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Catalog Management</h1>
        <p>Total items: <?= count($items) ?></p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <td><?= htmlspecialchars($item[$col] ?? '') ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
