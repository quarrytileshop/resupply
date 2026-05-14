<?php
/**
 * resupply - Customer Dashboard
 * Updated for new folder structure (May 14, 2026)
 * All includes, asset paths, and internal links updated
 */

$page_title = "Dashboard";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Ensure user is logged in (header already checks, but double-safe)
if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'User') ?>!</h1>
            <p class="lead text-muted">Here's what's happening with your resupply today.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <img src="assets/icons/shopping-cart.png" alt="New Order" style="width:64px; height:64px;" class="mb-3">
                    <h5 class="card-title">New Order</h5>
                    <p class="card-text text-muted">Create a new purchase order quickly</p>
                    <a href="orders/order.php" class="btn btn-primary w-100">Start New Order</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <img src="assets/icons/list.png" alt="Shopping Lists" style="width:64px; height:64px;" class="mb-3">
                    <h5 class="card-title">Shopping Lists</h5>
                    <p class="card-text text-muted">Manage your saved lists</p>
                    <a href="shopping_lists.php" class="btn btn-success w-100">View My Lists</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <img src="assets/icons/build.png" alt="Build List" style="width:64px; height:64px;" class="mb-3">
                    <h5 class="card-title">Build New List</h5>
                    <p class="card-text text-muted">Create a custom shopping list</p>
                    <a href="shopping_list_builder.php" class="btn btn-warning w-100">Build List</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders &amp; Activity</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Simple recent orders query (adjust table/columns to your actual schema)
                    $stmt = $pdo->prepare("SELECT * FROM orders 
                                           WHERE user_id = ? 
                                           ORDER BY created_at DESC LIMIT 5");
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
                        <p class="text-muted">No recent orders yet. Time to place your first order!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center text-muted small">
        Need help? Contact your organization admin or <a href="mailto:support@quarrytileshop.com">support</a>.
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>