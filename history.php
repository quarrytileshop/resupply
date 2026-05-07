<?php
// history.php – Modified March 11, 2025 19:00 PDT – Lines: 152
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT organization_id, is_propane, is_organization_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$organization_id = $user['organization_id'];
$is_propane = $user['is_propane'];
$is_organization_admin = $user['is_organization_admin'];

// Fetch organization orders
$stmt = $pdo->prepare("SELECT o.id, o.po_number, o.created_at, o.status, u.username AS ordered_by FROM orders o JOIN users u ON o.user_id = u.id WHERE u.organization_id = :organization_id ORDER BY o.created_at DESC");
$stmt->execute(['organization_id' => $organization_id]);
$orders = $stmt->fetchAll();

// Fetch archived checkbox lists
$stmt = $pdo->prepare("SELECT id, name, creator_id, items, created_at FROM checkbox_lists WHERE organization_id = :organization_id AND archived = 1 ORDER BY created_at DESC");
$stmt->execute(['organization_id' => $organization_id]);
$archived_lists = $stmt->fetchAll();

// Fetch SKU totals and last ordered
$stmt = $pdo->prepare("SELECT oi.catalog_item_id, ci.sku, SUM(oi.quantity) AS total, MAX(o.created_at) AS last_order FROM order_items oi JOIN orders o ON oi.order_id = o.id JOIN catalog_items ci ON oi.catalog_item_id = ci.id JOIN users u ON o.user_id = u.id WHERE u.organization_id = :organization_id GROUP BY oi.catalog_item_id");
$stmt->execute(['organization_id' => $organization_id]);
$sku_stats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Order History</h1>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Orders</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Ordered By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['ordered_by']); ?></td>
                                    <td><?php echo $order['status']; ?></td>
                                    <td><a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Archived Checkbox Lists</h3>
                <div class="accordion" id="archivedAccordion">
                    <?php foreach ($archived_lists as $list): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#archived<?php echo $list['id']; ?>">
                                    <?php echo htmlspecialchars($list['name']); ?> (<?php echo date('Y-m-d', strtotime($list['created_at'])); ?>)
                                </button>
                            </h2>
                            <div id="archived<?php echo $list['id']; ?>" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Checked</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>SKU</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $items = json_decode($list['items'], true); foreach ($items as $item): ?>
                                                    <tr>
                                                        <td><input type="checkbox" <?php if ($item['checked']) echo 'checked'; ?> disabled></td>
                                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                        <td><?php echo $item['quantity']; ?></td>
                                                        <td><?php echo htmlspecialchars($item['sku'] ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h3>SKU Stats</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Total Ordered</th>
                                <th>Last Ordered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sku_stats as $stat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($stat['sku']); ?></td>
                                    <td><?php echo $stat['total']; ?></td>
                                    <td><?php echo $stat['last_order'] ? date('Y-m-d', strtotime($stat['last_order'])) : 'N/A'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
