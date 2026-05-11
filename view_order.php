<?php
// view_order.php – Modified 2026-05-08 – Lines: 130
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

// Fetch order details
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name 
                       FROM orders o 
                       JOIN users u ON o.user_id = u.id 
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
    <title>Order #<?= htmlspecialchars($order['po_number'] ?? $order['id']) ?> - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1>Order #<?= htmlspecialchars($order['po_number'] ?? $order['id']) ?></h1>
        <p><strong>Date:</strong> <?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></p>
        <p><strong>Ordered by:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></p>

        <div class="card mt-4">
            <div class="card-body">
                <h5>Order Details</h5>
                <pre><?= htmlspecialchars(print_r($order, true)) ?></pre>
                <!-- TODO: Replace with nice item table later -->
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
