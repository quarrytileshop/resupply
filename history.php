<?php
// history.php – Full rewrite with original logic – Updated 2026-05-11
$page_title = "Order History - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's order history (your original query – fully preserved)
$stmt = $pdo->prepare("SELECT o.*, COUNT(oi.id) as item_count 
                       FROM orders o 
                       LEFT JOIN order_items oi ON o.id = oi.order_id 
                       WHERE o.user_id = :user_id 
                       GROUP BY o.id 
                       ORDER BY o.created_at DESC");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Order History</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">No orders yet.</div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['po_number'] ?? $order['id']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></td>
                                <td><?= ucfirst(htmlspecialchars($order['fulfillment_type'] ?? 'general')) ?></td>
                                <td><?= $order['item_count'] ?> items</td>
                                <td><span class="badge bg-success">Sent</span></td>
                                <td><a href="view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
