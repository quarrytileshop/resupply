<?php
// admin_dashboard.php – Modified 2026-05-08 – Lines: 95
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
    <style>
        .admin-card {
            transition: transform 0.2s;
        }
        .admin-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin_organizations.php">Organizations</a>
                <a class="nav-link" href="admin_users.php">Users</a>
                <a class="nav-link" href="admin_catalog.php">Catalog</a>
                <a class="nav-link" href="admin_orders.php">Orders</a>
                <a class="nav-link text-danger" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-5 text-center">Admin Dashboard</h1>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <a href="admin_organizations.php" class="text-decoration-none">
                    <div class="card admin-card h-100 text-center p-4 border-primary">
                        <h3>Manage Organizations</h3>
                        <p class="text-muted">Approve, view, and manage all organizations</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="admin_users.php" class="text-decoration-none">
                    <div class="card admin-card h-100 text-center p-4 border-primary">
                        <h3>Manage Users</h3>
                        <p class="text-muted">Add, edit, suspend users</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="admin_catalog.php" class="text-decoration-none">
                    <div class="card admin-card h-100 text-center p-4 border-primary">
                        <h3>Catalog Management</h3>
                        <p class="text-muted">Products, pricing, images</p>
                    </div>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="admin_orders.php" class="text-decoration-none">
                    <div class="card admin-card h-100 text-center p-4 border-primary">
                        <h3>View All Orders</h3>
                        <p class="text-muted">Recent orders and history</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
