<?php
// view_order.php – Modified 2026-05-08 – Lines: 160
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = intval($_GET['id'] ?? 0);

if ($order_id === 0) {
    header("Location: history.php");
    exit;
}

// Fetch order + items (basic for now)
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, org.name as organization_name 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.id 
                       LEFT JOIN organizations org ON u.organization_id = org.id 
                       WHERE o.id = :id");
$stmt->execute(['id' => $order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?= htmlspecialchars($order['po_number'] ?? $order_id) ?> - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1>Order #<?= htmlspecialchars($order['po_number'] ?? $order_id) ?></h1>
        <p><strong>Date:</strong> <?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>
        <p><strong>Organization:</strong> <?= htmlspecialchars($order['organization_name'] ?? '—') ?></p>

        <div class="card mt-4">
            <div class="card-header">
                <strong>Order Items</strong>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    Full itemized list with quantities and prices will appear here in the next update.
                </div>
                <!-- Placeholder for order items table -->
            </div>
        </div>

        <div class="mt-4">
            <a href="history.php" class="btn btn-secondary">← Back to History</a>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
