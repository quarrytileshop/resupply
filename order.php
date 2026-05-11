<?php
// order.php – Modified 2026-05-08 – Lines: 180
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
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

        <!-- Order Type Tabs -->
        <ul class="nav nav-tabs mb-4" id="orderTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#general">General Products</a></li>
            <?php if ($is_propane): ?>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#propane">Propane</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#paint">Paint</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#checkbox">Checkbox List</a></li>
        </ul>

        <div class="tab-content">
            <!-- General Products Tab -->
            <div class="tab-pane fade show active" id="general">
                <div class="alert alert-info">
                    Shopping lists will appear here. Quantities update instantly.
                </div>
                <p><em>(Full shopping list + cart interface coming soon)</em></p>
            </div>

            <!-- Propane Tab -->
            <div class="tab-pane fade" id="propane">
                <div class="alert alert-warning">
                    Propane Order Form – Simple exchange / new tanks
                </div>
                <p><em>(Propane form will go here)</em></p>
            </div>

            <!-- Paint Tab -->
            <div class="tab-pane fade" id="paint">
                <div class="alert alert-info">
                    Guided Paint Order (size, type, sheen, color…)
                </div>
                <p><em>(Paint guided questions will go here)</em></p>
            </div>

            <!-- Checkbox Tab -->
            <div class="tab-pane fade" id="checkbox">
                <div class="alert alert-secondary">
                    Create Checkbox List for in-store / team use
                </div>
                <p><em>(Checkbox list builder coming soon)</em></p>
            </div>
        </div>

        <div class="mt-5">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
