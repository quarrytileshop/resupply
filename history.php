<?php
/**
 * resupply - Order History (FINAL README-Aligned)
 * Now shows full details + archive links
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Order History';

require_once 'includes/header.php';

$stmt = $pdo->prepare("
    SELECT * FROM orders 
    WHERE (organization_id = ? OR user_id = ?) 
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['organization_id'] ?? 0, $_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<h1 class="mb-4">Order History</h1>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">No orders yet.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></td>
                    <td><?= ucfirst($order['order_type']) ?></td>
                    <td><span class="badge bg-success">Completed</span></td>
                    <td><a href="<?= BASE_URL ?>orders/view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>