<?php
/**
 * resupply - Header Include
 * Updated for new folder structure (May 14, 2026)
 * Added visible "Logout" button (bypasses broken dropdown)
 */

require_once 'config.php';

// Redirect logic
if (is_logged_in() && in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'index.php'])) {
    if (is_super_admin()) {
        header("Location: admin/admin_dashboard.php");
    } elseif (is_vendor()) {
        header("Location: vendor/vendor_dashboard.php");
    } elseif (is_org_admin()) {
        header("Location: organization_admin.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

if (!is_logged_in() && !in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'index.php'])) {
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
    
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/icons/logo-192.png">
    <link rel="apple-touch-icon" href="/assets/icons/logo-192.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="manifest" href="/Manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .navbar { background-color: #2c3e50; }
        .nav-link { color: #ecf0f1 !important; }
        .nav-link:hover { color: #3498db !important; }
    </style>
</head>
<body>
<?php if (is_logged_in()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">
                <img src="/assets/icons/logo-512.png" alt="Quarry Tile Shop" height="40">
                Resupply
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_catalog.php">Catalog</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_vendor()): ?>
                        <li class="nav-item"><a class="nav-link" href="../vendor/vendor_dashboard.php">Vendor Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="../vendor/vendor_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="../vendor/vendor_shopping_lists.php">Shopping Lists</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_org_admin() || is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="../organization_admin.php">Org Admin</a></li>
                    <?php endif; ?>
                    
                    <li class="nav-item"><a class="nav-link" href="../dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../shopping_lists.php">Shopping Lists</a></li>
                    <li class="nav-item"><a class="nav-link" href="../shopping_list_builder.php">Build List</a></li>
                    <li class="nav-item"><a class="nav-link" href="../orders/order.php">New Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="../history.php">Order History</a></li>
                </ul>
                
                <!-- VISIBLE LOGOUT BUTTON -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
<?php else: ?>
<?php endif; ?>

<?php
if (isset($_SESSION['message']) && is_logged_in()) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($_SESSION['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['message']);
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>