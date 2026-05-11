<?php
// order.php – Modified 2026-05-08 – Lines: 120
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// TODO: Add full order logic later (General / Propane / Paint tabs)
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <p class="text-muted">Select order type below</p>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="#" class="btn btn-primary btn-lg w-100 py-5 text-center">
                    <strong>General Products</strong><br>
                    <small>Shopping lists &amp; catalog</small>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-warning btn-lg w-100 py-5 text-center">
                    <strong>Propane</strong><br>
                    <small>Exchange / New tanks</small>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-info btn-lg w-100 py-5 text-center">
                    <strong>Paint</strong><br>
                    <small>Guided color selection</small>
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
