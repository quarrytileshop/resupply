<?php
// dashboard.php – Updated 2026-05-11 to use header + footer
$page_title = "Dashboard - Resupply Rocket";
require_once 'header.php';

// Get user info (already fetched in header, but we can reuse)
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.*, o.name as organization_name, o.account_number 
                       FROM users u 
                       LEFT JOIN organizations o ON u.organization_id = o.id 
                       WHERE u.id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>

<div class="container mt-4">
    <h1 class="mb-1">Welcome back, <?= htmlspecialchars($user['first_name']) ?>! 🚀</h1>
    <p class="text-muted">Organization: <?= htmlspecialchars($user['organization_name'] ?? '—') ?></p>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <a href="order.php" class="btn btn-primary btn-lg w-100 py-4 text-center">
                <strong>New Order</strong><br>
                <small>General • Paint • Propane</small>
            </a>
        </div>
        <div class="col-md-4">
            <a href="history.php" class="btn btn-secondary btn-lg w-100 py-4 text-center">
                <strong>Order History</strong><br>
                <small>View past orders</small>
            </a>
        </div>
        <div class="col-md-4">
            <a href="checkbox_create.php" class="btn btn-info btn-lg w-100 py-4 text-center">
                <strong>Checkbox List</strong><br>
                <small>In-store / team list</small>
            </a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
