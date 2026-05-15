<?php
/**
 * resupply - New Order Page (FINAL Generic Version)
 * Changed "General Tile" to "General Order" – now suitable for any material type
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

$page_title = 'New Order';
$order_type = $_GET['type'] ?? 'general';

require_once '../includes/header.php';
?>

<h1 class="mb-4">New <?= ucfirst($order_type) ?> Order</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>orders/save_order.php">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <input type="hidden" name="order_type" value="<?= htmlspecialchars($order_type) ?>">

            <!-- Order Type Tabs – Generic labels -->
            <div class="btn-group w-100 mb-4" role="group">
                <a href="?type=general" class="btn btn-outline-primary <?= $order_type==='general'?'active':'' ?>">General Order</a>
                <a href="?type=propane" class="btn btn-outline-primary <?= $order_type==='propane'?'active':'' ?>">Propane</a>
                <a href="?type=paint" class="btn btn-outline-primary <?= $order_type==='paint'?'active':'' ?>">Paint &amp; Supplies</a>
                <a href="?type=checkbox" class="btn btn-outline-primary <?= $order_type==='checkbox'?'active':'' ?>">Checkbox List</a>
            </div>

            <div class="mb-4">
                <label class="form-label">Order Items</label>
                <textarea name="items" class="form-control" rows="10" placeholder="Enter your items here... (any materials or products)"></textarea>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">🚀 Send It!</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>