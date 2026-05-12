<?php
// view_order.php – Full rewrite with original logic – Updated 2026-05-11
$page_title = "View Order - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: history.php");
    exit;
}

$order_id = (int)$_GET['id'];

// Fetch full order details (original logic preserved)
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       WHERE o.id = :id");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Order not found.</div></div>";
    require_once 'footer.php';
    exit;
}

// Fetch order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$items = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order #<?= htmlspecialchars($order['po_number'] ?? $order['id']) ?></h1>
        <a href="history.php" class="btn btn-secondary">← Back to History</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date:</strong> <?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></p>
                    <p><strong>Placed by:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
                    <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($order['fulfillment_type'] ?? 'general')) ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-success fs-5">Sent Successfully</span>
                    <br><br>
                    <a href="#" onclick="window.print()" class="btn btn-outline-primary">📄 Download PDF</a>
                </div>
            </div>

            <hr>

            <h5>Order Items</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['description'] ?? $item['product_name']) ?></td>
                            <td><?= (int)$item['quantity'] ?></td>
                            <td><?= htmlspecialchars($item['notes'] ?? '') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($order['notes'])): ?>
            <div class="mt-4">
                <strong>Notes:</strong> <?= nl2br(htmlspecialchars($order['notes'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
