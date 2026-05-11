<?php
// dashboard.php – Modified 2026-05-08 – Lines: 140
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT u.*, o.name as organization_name, o.account_number 
                       FROM users u 
                       LEFT JOIN organizations o ON u.organization_id = o.id 
                       WHERE u.id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Resupply Rocket</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="order.php">New Order</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Welcome back, <?= htmlspecialchars($user['first_name']) ?>!</h1>
        <p class="text-muted">Organization: <?= htmlspecialchars($user['organization_name'] ?? '—') ?></p>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <a href="order.php" class="btn btn-primary btn-lg w-100 py-4">
                    <strong>New Order</strong><br>
                    <small>General / Paint / Propane</small>
                </a>
            </div>
            <div class="col-md-4">
                <a href="history.php" class="btn btn-secondary btn-lg w-100 py-4">
                    <strong>Order History</strong><br>
                    <small>View past orders</small>
                </a>
            </div>
            <div class="col-md-4">
                <a href="checkbox_create.php" class="btn btn-info btn-lg w-100 py-4">
                    <strong>Checkbox List</strong><br>
                    <small>In-store / team list</small>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
