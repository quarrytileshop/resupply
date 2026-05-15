<?php
/**
 * resupply - Header Include (FINAL Clean Version)
 * Fixed blank header, robust vendor redirect, no placeholders
 * Date: May 15, 2026
 */

require_once __DIR__ . '/config.php';

// Robust redirect for vendors
if (is_logged_in()) {
    $current = basename($_SERVER['PHP_SELF']);
    if ($current === 'dashboard.php' && is_vendor()) {
        header("Location: " . BASE_URL . "vendor/vendor_dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= htmlspecialchars($page_title ?? 'Resupply Rocket') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php if (is_logged_in()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>dashboard.php">
                <i class="fas fa-rocket"></i> Resupply Rocket
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (is_super_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/admin_dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <?php if (is_vendor()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vendor/vendor_dashboard.php">Vendor Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>shopping_lists.php">Shopping Lists</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>orders/order.php">New Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>history.php">History</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= BASE_URL ?>logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
<?php endif; ?>

<?php
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show">' . htmlspecialchars($_SESSION['message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show">' . htmlspecialchars($_SESSION['error']) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    unset($_SESSION['error']);
}
?>