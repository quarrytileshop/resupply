<?php
// dashboard.php – Modified 2026-05-08
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1>Welcome, <?= htmlspecialchars($user['first_name']) ?>!</h1>
        <p><a href="logout.php" class="btn btn-danger">Logout</a></p>

        <div class="row g-3">
            <div class="col-md-4"><a href="order.php" class="btn btn-primary w-100">New Order</a></div>
            <div class="col-md-4"><a href="history.php" class="btn btn-secondary w-100">Order History</a></div>
            <div class="col-md-4"><a href="admin_organizations.php" class="btn btn-info w-100">Manage Organizations</a></div>
        </div>
    </div>
</body>
</html>
