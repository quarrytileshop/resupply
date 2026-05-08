<?php
// admin_dashboard.php – Modified 2026-05-08
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="admin_organizations.php" class="btn btn-primary btn-lg w-100 py-4">Manage Organizations</a>
            </div>
            <div class="col-md-4">
                <a href="admin_users.php" class="btn btn-primary btn-lg w-100 py-4">Manage Users</a>
            </div>
            <div class="col-md-4">
                <a href="admin_catalog.php" class="btn btn-primary btn-lg w-100 py-4">Catalog Management</a>
            </div>
            <div class="col-md-4">
                <a href="admin_orders.php" class="btn btn-primary btn-lg w-100 py-4">View Orders</a>
            </div>
        </div>
    </div>
</body>
</html>
