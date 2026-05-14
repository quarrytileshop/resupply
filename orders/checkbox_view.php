<?php
/**
 * resupply - Checkbox View Order Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Checkbox Order View - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$order_id = (int)($_GET['id'] ?? 0);
if ($order_id <= 0) {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: ../history.php");
    exit;
}

// Fetch the checkbox-style order (preserves original logic)
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id AND order_type = 'checkbox'");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch();

if (!$order) {
    $_SESSION['error'] = "Checkbox order not found.";
    header("Location: ../history.php");
    exit;
}

// Fetch order items (checkbox style)
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id");
$stmt->execute(['order_id' => $order_id]);
$items = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Checkbox Order #<?= htmlspecialchars($order['id']) ?></h1>
    <p class="text-muted">Created on <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Selected Items</h5>
        </div>
        <div class="card-body">
            <?php if ($items): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name'] ?? 'Unknown Product') ?></td>
                                <td class="text-end"><?= (int)$item['quantity'] ?></td>
                                <td class="text-end">$<?= number_format($item['price'] ?? 0, 2) ?></td>
                                <td class="text-end">$<?= number_format(($item['quantity'] * ($item['price'] ?? 0)), 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No items found in this checkbox order.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="../history.php" class="btn btn-secondary">← Back to History</a>
        <a href="../orders/order.php" class="btn btn-primary ms-3">Create Another Order</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>