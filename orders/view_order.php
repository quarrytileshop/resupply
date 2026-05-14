<?php
/**
 * resupply - Detailed View Order Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "View Order Details - Resupply Rocket";
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

// Fetch order details (preserves original logic - adjust query to match your actual schema)
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, o.vendor_name, o.vendor_email 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       WHERE o.id = :id");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch();

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: ../history.php");
    exit;
}

// Fetch order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id");
$stmt->execute(['order_id' => $order_id]);
$items = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Order #<?= htmlspecialchars($order['id']) ?> - Detailed View</h1>
    <p class="text-muted">Placed on <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?> by <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Status:</strong> 
                    <span class="badge bg-<?= $order['status'] === 'sent' ? 'success' : 'warning' ?>">
                        <?= htmlspecialchars(ucfirst($order['status'] ?? 'Pending')) ?>
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Type:</strong> <?= htmlspecialchars($order['order_type'] ?? 'General') ?>
                </div>
                <div class="col-md-12 mt-3">
                    <strong>Vendor:</strong> <?= htmlspecialchars($order['vendor_name'] ?? 'Not assigned') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Order Items</h5>
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
                <p class="text-muted">No items found in this order.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Action buttons -->
    <div class="d-flex gap-3">
        <a href="../history.php" class="btn btn-secondary">← Back to History</a>
        
        <?php if ($order['status'] !== 'sent'): ?>
            <form method="post" action="../send_po_email.php" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="vendor_email" value="<?= htmlspecialchars($order['vendor_email'] ?? '') ?>">
                <input type="hidden" name="vendor_name" value="<?= htmlspecialchars($order['vendor_name'] ?? '') ?>">
                <button type="submit" class="btn btn-success">Send PO Email to Vendor</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>