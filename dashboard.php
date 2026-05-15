<?php
/**
 * resupply - Customer Dashboard (MAIN DASHBOARD)
 * Updated for new folder structure (May 14, 2026)
 * Clean layout with existing icons only
 */

$page_title = "Dashboard";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12 text-center text-md-start">
            <h1 class="mb-1">Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'User') ?>!</h1>
            <p class="lead text-muted">Here's what's happening with your resupply today.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <img src="/assets/icons/general.png" alt="New Order" style="width:64px;height:64px;" class="mb-3">
                    <h5 class="card-title">New Order</h5>
                    <p class="card-text text-muted">Create a new purchase order quickly</p>
                    <a href="orders/order.php" class="btn btn-dark w-100 mt-auto">Start New Order</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <img src="/assets/icons/general.png" alt="Shopping Lists" style="width:64px;height:64px;" class="mb-3">
                    <h5 class="card-title">Shopping Lists</h5>
                    <p class="card-text text-muted">Manage your saved lists</p>
                    <a href="shopping_lists.php" class="btn btn-success w-100 mt-auto">View My Lists</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <img src="/assets/icons/general.png" alt="Build List" style="width:64px;height:64px;" class="mb-3">
                    <h5 class="card-title">Build New List</h5>
                    <p class="card-text text-muted">Create a custom shopping list</p>
                    <a href="shopping_list_builder.php" class="btn btn-warning w-100 mt-auto text-dark">Build List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Orders &amp; Activity</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->execute([$_SESSION['user_id']]);
                    $recent = $stmt->fetchAll();
                    ?>
                    <?php if ($recent): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['id']) ?></td>
                                        <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($order['order_type'] ?? 'General') ?></td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td><a href="orders/view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">No recent orders yet. Time to place your first order!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center text-muted small">
        Need help? Contact your organization admin or <a href="mailto:support@quarrytileshop.com" class="text-decoration-none">support</a>.
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>