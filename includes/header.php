<?php
/**
 * resupply - Header Include (Professional Rewrite)
 * FIXED: All navigation now uses ROOT-RELATIVE paths (/dashboard.php)
 * Fixed: Works from root, admin/, orders/, vendor/ without ../ errors
 * Added: Proper role-based nav + visible logout + CSRF ready
 * Date: May 15, 2026
 */

require_once __DIR__ . '/config.php';

// ======================
// AUTO-REDIRECT LOGIC (now works from any folder)
// ======================
$current_page = basename($_SERVER['PHP_SELF']);

if (is_logged_in() && in_array($current_page, ['login.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'index.php'])) {
    if (is_super_admin()) {
        header("Location: " . BASE_URL . "admin/admin_dashboard.php");
    } elseif (is_vendor()) {
        header("Location: " . BASE_URL . "vendor/vendor_dashboard.php");
    } elseif (is_org_admin()) {
        header("Location: " . BASE_URL . "organization_admin.php");
    } else {
        header("Location: " . BASE_URL . "dashboard.php");
    }
    exit;
}

if (!is_logged_in() && !in_array($current_page, ['login.php', 'register.php', 'forgot_password.php', 'reset_password.php', 'index.php'])) {
    header("Location: " . BASE_URL . "login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional B2B Resupply Portal for Quarry Tile Shop">
    <title><?= SITE_NAME ?> - <?= isset($page_title) ? htmlspecialchars($page_title) : 'Resupply Rocket' ?></title>
    
    <link rel="icon" type="image/png" sizes="192x192" href="<?= ASSETS_URL ?>icons/logo-192.png">
    <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>icons/logo-192.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/styles.css">
    <link rel="manifest" href="<?= BASE_URL ?>Manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .navbar { background-color: #2c3e50; }
        .nav-link { color: #ecf0f1 !important; }
        .nav-link:hover { color: #3498db !important; }
        .navbar-brand img { filter: brightness(1.1); }
    </style>
</head>
<body>
<?php if (is_logged_in()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>dashboard.php">
                <img src="<?= ASSETS_URL ?>icons/logo-512.png" alt="Quarry Tile Shop" height="40" class="me-2">
                Resupply
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admin_dashboard.php">Admin Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admin_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admin_users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admin_catalog.php">Catalog</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_vendor()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vendor/vendor_dashboard.php">Vendor Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vendor/vendor_organizations.php">Organizations</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vendor/vendor_shopping_lists.php">Shopping Lists</a></li>
                    <?php endif; ?>
                    
                    <?php if (is_org_admin() || is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>organization_admin.php">Org Admin</a></li>
                    <?php endif; ?>
                    
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>shopping_lists.php">Shopping Lists</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>shopping_list_builder.php">Build List</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>orders/order.php">New Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>history.php">Order History</a></li>
                </ul>
                
                <!-- Visible Logout (always works) -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="<?= BASE_URL ?>logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
<?php endif; ?>

<?php
// Auto-display session messages
if (isset($_SESSION['message']) && is_logged_in()) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($_SESSION['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['error']) && is_logged_in()) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo htmlspecialchars($_SESSION['error']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['error']);
}
?>