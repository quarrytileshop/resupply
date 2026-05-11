<?php
// order.php – Modified 2026-05-08 – Lines: 180
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$is_propane = $_SESSION['is_propane'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Resupply Rocket</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link active" href="order.php">New Order</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>New Order</h1>
        <p class="text-muted">Choose the type of order you want to place</p>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="general_order.php" class="text-decoration-none">
                    <div class="card h-100 text-center p-5 border-primary">
                        <h4>General Products</h4>
                        <p class="text-muted">Shopping lists, catalog items, manual entries</p>
                    </div>
                </a>
            </div>
            <?php if ($is_propane): ?>
            <div class="col-md-4">
                <a href="propane_order.php" class="text-decoration-none">
                    <div class="card h-100 text-center p-5 border-warning">
                        <h4>Propane</h4>
                        <p class="text-muted">Tank exchanges &amp; new fills</p>
                    </div>
                </a>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
                <a href="paint_order.php" class="text-decoration-none">
                    <div class="card h-100 text-center p-5 border-info">
                        <h4>Paint Order</h4>
                        <p class="text-muted">Guided color &amp; finish selection</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="checkbox_create.php" class="text-decoration-none">
                    <div class="card h-100 text-center p-5 border-secondary">
                        <h4>Checkbox List</h4>
                        <p class="text-muted">In-store or team checklist</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
