<?php
/**
 * resupply - Header Include
 * Updated for new folder structure (May 14, 2026)
 * Favicon points to logo-192.png
 * Navigation now ONLY shows for logged-in users
 */

require_once 'includes/config.php';   // Fixed path

// Redirect non-logged-in users away from protected pages
if (!is_logged_in() && basename($_SERVER['PHP_SELF']) !== 'login.php' 
                    && basename($_SERVER['PHP_SELF']) !== 'register.php' 
                    && basename($_SERVER['PHP_SELF']) !== 'forgot_password.php' 
                    && basename($_SERVER['PHP_SELF']) !== 'reset_password.php'
                    && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Quarry Tile Shop Resupply Portal">
    <title><?= SITE_NAME ?> - <?= isset($page_title) ? $page_title : 'Resupply Rocket' ?></title>
    
    <!-- Favicon pointing to logo-192 -->
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/icons/logo-192.png">
    <link rel="apple-touch-icon" href="/assets/icons/logo-192.png">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/styles.css">
    
    <!-- Manifest for PWA -->
    <link rel="manifest" href="/Manifest.json">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .navbar { background-color: #2c3e50; }
        .nav-link { color: #ecf0f1 !important; }
        .nav-link:hover { color: #3498db !important; }
    </style>
</head>
<body>
<?php if (is_logged_in()): ?>
    <!-- NAVIGATION ONLY FOR LOGGED-IN USERS -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <img src="/assets/icons/logo-512.png" alt="Quarry Tile Shop" height="40">
                Resupply
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/admin_dashboard.php">Admin Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin/admin_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin/admin_users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin/admin_catalog.php">Catalog</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_vendor()): ?>
                        <li class="nav-item"><a class="nav-link" href="vendor/vendor_dashboard.php">Vendor Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="vendor/vendor_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="vendor/vendor_shopping_lists.php">Shopping Lists</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_org_admin() || is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="organization_admin.php">Org Admin</a></li>
                    <?php endif; ?>
                    
                    <!-- Customer links -->
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="shopping_lists.php">Shopping Lists</a></li>
                    <li class="nav-item"><a class="nav-link" href="shopping_list_builder.php">Build List</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders/order.php">New Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="history.php">Order History</a></li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> 
                            <?= htmlspecialchars($_SESSION['first_name'] ?? 'User') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="record_usage.php">Record Usage</a></li>
                            <?php if (is_super_admin()): ?>
                                <li><a class="dropdown-item" href="admin/admin_impersonate.php">Impersonate User</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>

<div class="container mt-4">
<?php
// Global messages
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($_SESSION['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['message']);
}
?>