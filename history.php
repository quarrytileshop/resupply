<?php
/**
 * resupply - Order History Page
 * Updated for new folder structure (May 14, 2026)
 * All includes, asset paths, and internal links updated
 */

$page_title = "Order History - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;

// Fetch order history (preserves original logic - adjust query to your actual schema)
$stmt = $pdo->prepare("SELECT * FROM orders 
                       WHERE (user_id = :user_id OR organization_id = :org_id)
                       ORDER BY created_at DESC");
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'org_id'  => $organization_id
]);
$orders = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Order History</h1>
    <p class="text-muted">All past orders for your organization.</p>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">No orders yet. <a href="orders/order.php">Place your first order</a>.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Total Items</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id'] ?? 'N/A') ?></td>
                        <td><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($order['order_type'] ?? 'General') ?></span></td>
                        <td><span class="badge bg-success"><?= htmlspecialchars($order['status'] ?? 'Completed') ?></span></td>
                        <td><?= htmlspecialchars($order['item_count'] ?? '—') ?></td>
                        <td>
                            <a href="orders/view_order.php?id=<?= $order['id'] ?>" 
                               class="btn btn-sm btn-outline-primary">View Details</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>