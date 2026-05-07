<?php
// view_order.php – Modified March 11, 2025 21:15 PDT – Lines: 248
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['id'] ?? 0);
if ($order_id <= 0) {
    header("Location: dashboard.php");
    exit;
}

// Fetch order details
$stmt = $pdo->prepare("SELECT o.*, u.username AS ordered_by, u.organization_id FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = :id");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch();
if (!$order) {
    header("Location: dashboard.php");
    exit;
}

// Check access: must be in same organization or admin
$stmt = $pdo->prepare("SELECT organization_id, is_admin, is_organization_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$current_user = $stmt->fetch();
if (!$current_user['is_admin'] && !$current_user['is_organization_admin'] && $current_user['organization_id'] != $order['organization_id']) {
    header("Location: dashboard.php");
    exit;
}

// Fetch order items
$stmt = $pdo->prepare("SELECT oi.*, ci.item_name, ci.sku, ci.price, ci.description FROM order_items oi JOIN catalog_items ci ON oi.catalog_item_id = ci.id WHERE oi.order_id = :order_id ORDER BY oi.id");
$stmt->execute(['order_id' => $order_id]);
$items = $stmt->fetchAll();

// Fetch paint details if any
$paint_items = [];
foreach ($items as &$item) {
    if ($item['paint_details']) {
        $item['paint_details'] = json_decode($item['paint_details'], true);
        $paint_items[] = $item;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Order #<?php echo $order_id; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Order #<?php echo $order_id; ?></h1>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Order Details</h5>
                <p><strong>PO Number:</strong> <?php echo htmlspecialchars($order['po_number'] ?: 'N/A'); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['status'])); ?></p>
                <p><strong>Created:</strong> <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Ordered By:</strong> <?php echo htmlspecialchars($order['ordered_by']); ?></p>
                <p><strong>Fulfillment Type:</strong> <?php echo htmlspecialchars(ucfirst($order['fulfillment_type'] ?? 'N/A')); ?></p>
                <p><strong>Delivery Address:</strong> <?php echo nl2br(htmlspecialchars($order['delivery_address'] ?? 'N/A')); ?></p>
                <p><strong>Internal Notes:</strong> <?php echo nl2br(htmlspecialchars($order['internal_notes'] ?? 'N/A')); ?></p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5>Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>SKU</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['description'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($item['sku'] ?: 'N/A'); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                                </tr>
                                <?php if (!empty($item['paint_details'])): ?>
                                    <tr>
                                        <td colspan="6" class="paint-details">
                                            <strong>Paint Details:</strong><br>
                                            Size: <?php echo htmlspecialchars($item['paint_details']['size'] ?? 'N/A'); ?><br>
                                            Type: <?php echo htmlspecialchars($item['paint_details']['type'] ?? 'N/A'); ?><br>
                                            Sheen: <?php echo htmlspecialchars($item['paint_details']['sheen'] ?? 'N/A'); ?><br>
                                            Brand: <?php echo htmlspecialchars($item['paint_details']['brand'] ?? 'N/A'); ?><br>
                                            Color: <?php echo htmlspecialchars($item['paint_details']['color'] ?? 'N/A'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="history.php" class="btn btn-secondary">Back to History</a>
        <?php if ($is_organization_admin || $current_user['is_admin']): ?>
            <a href="admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
