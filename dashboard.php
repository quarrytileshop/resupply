<?php
/**
 * resupply - Customer Dashboard (README-Aligned Rewrite)
 * Dismissable admin messages + last 3 orders
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Dashboard';

require_once 'includes/header.php';

// Example dismissable message (admin-placed)
$messageStmt = $pdo->prepare("SELECT * FROM admin_messages WHERE user_id = ? OR organization_id = ? LIMIT 1");
$messageStmt->execute([$_SESSION['user_id'], $_SESSION['organization_id'] ?? 0]);
$msg = $messageStmt->fetch();
?>

<h1 class="mb-4">Welcome back, <?= htmlspecialchars($_SESSION['email']) ?>!</h1>

<?php if ($msg): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg['message']) ?>
        <button type="button" class="btn-close" onclick="dismissMessage(<?= $msg['id'] ?>)" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>New Order</h5>
                <a href="<?= BASE_URL ?>orders/order.php" class="btn btn-success btn-lg w-100 mt-3">Start Order</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>Shopping Lists</h5>
                <a href="<?= BASE_URL ?>shopping_lists.php" class="btn btn-primary btn-lg w-100 mt-3">View Lists</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>Last 3 Orders</h5>
                <div class="list-group mt-3">
                    <!-- Last 3 orders would load here in full version -->
                    <div class="list-group-item">Order #00123 — Today</div>
                    <div class="list-group-item">Order #00122 — Yesterday</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>