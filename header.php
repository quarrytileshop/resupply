<?php
// header.php – Updated for dedicated is_vendor_admin role separation – 2026-05-12
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_id']) && $current_page !== 'login.php' && $current_page !== 'register.php' && $current_page !== 'forgot_password.php') {
    header("Location: login.php");
    exit;
}

$user_info = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.is_admin, u.is_vendor_admin, u.is_organization_admin, o.name as organization_name 
                           FROM users u 
                           LEFT JOIN organizations o ON u.organization_id = o.id 
                           WHERE u.id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user_info = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Resupply Rocket'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--navy); box-shadow: 0 4px 12px rgba(10, 37, 64, 0.3);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php 
                if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) echo 'admin_dashboard.php';
                elseif (isset($_SESSION['is_vendor_admin']) && $_SESSION['is_vendor_admin']) echo 'vendor_dashboard.php';
                elseif (isset($_SESSION['is_organization_admin']) && $_SESSION['is_organization_admin']) echo 'organization_admin.php';
                else echo 'dashboard.php';
            ?>">
                <img src="icons/logo-192.png" alt="Resupply Rocket" style="max-height: 38px; margin-right: 10px;">
                <span class="fw-bold fs-4">Resupply Rocket</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <!-- SUPER ADMIN ONLY -->
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">Super Admin</a></li>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Regular Dashboard</a></li>

                    <?php elseif (isset($_SESSION['is_vendor_admin']) && $_SESSION['is_vendor_admin']): ?>
                        <!-- VENDOR ADMIN ONLY -->
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'vendor_dashboard.php' ? 'active' : ''; ?>" href="vendor_dashboard.php">Vendor Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'shopping_list_builder.php' ? 'active' : ''; ?>" href="shopping_list_builder.php">Build Lists</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'vendor_shopping_lists.php' ? 'active' : ''; ?>" href="vendor_shopping_lists.php">My Lists</a></li>
                        <li class="nav-item"><a class="nav-link" href="vendor_organizations.php">My Customers</a></li>

                    <?php elseif (isset($_SESSION['is_organization_admin']) && $_SESSION['is_organization_admin']): ?>
                        <!-- ORGANIZATION ADMIN ONLY -->
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'organization_admin.php' ? 'active' : ''; ?>" href="organization_admin.php">Organization Admin</a></li>

                    <?php else: ?>
                        <!-- REGULAR USER -->
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'order.php' ? 'active' : ''; ?>" href="order.php">New Order</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $current_page === 'history.php' ? 'active' : ''; ?>" href="history.php">History</a></li>
                        <li class="nav-item"><a class="nav-link" href="shopping_lists.php">My Shopping Lists</a></li>
                    <?php endif; ?>

                    <?php if ($user_info): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <span class="me-2"><?= htmlspecialchars($user_info['first_name']) ?></span>
                            <small class="text-teal">
                                <?= $user_info['is_admin'] ? 'Super Admin' : 
                                    ($user_info['is_vendor_admin'] ? 'Vendor Admin' : 
                                    ($user_info['is_organization_admin'] ? 'Org Admin' : htmlspecialchars($user_info['organization_name'] ?? ''))) ?>
                            </small>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
