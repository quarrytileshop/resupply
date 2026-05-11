<?php
// admin_catalog.php – Final Clean Version – Modified 2026-05-08
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM catalog_items ORDER BY item_name");
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Catalog Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <div class="navbar-nav">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                <a class="nav-link" href="admin_organizations.php">Organizations</a>
                <a class="nav-link" href="admin_users.php">Users</a>
                <a class="nav-link active" href="admin_catalog.php">Catalog</a>
                <a class="nav-link" href="admin_orders.php">Orders</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Catalog Management</h1>
        <p class="text-muted">Total items: <?= count($items) ?></p>

        <a href="#" class="btn btn-success mb-3">+ Add New Product</a>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width:80px;">Image</th>
                        <th>Item Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['sku'] ?? '—') ?></td>
                        <td>$<?= number_format($item['price'] ?? 0, 2) ?></td>
                        <td><?= htmlspecialchars($item['item_type'] ?? 'general') ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary me-1">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
