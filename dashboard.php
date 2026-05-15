<?php
require_once 'includes/config.php';

$page_title = 'Dashboard';

if (is_vendor()) {
    header("Location: " . BASE_URL . "vendor/vendor_dashboard.php");
    exit;
}

require_once 'includes/header.php';
?>

<h1 class="mb-4">Welcome to Resupply Rocket</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>New Order</h5>
                <a href="<?= BASE_URL ?>orders/order.php" class="btn btn-success btn-lg w-100 mt-3">Start New Order</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>Shopping Lists</h5>
                <a href="<?= BASE_URL ?>shopping_lists.php" class="btn btn-primary btn-lg w-100 mt-3">View All Lists</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
                <h5>Order History</h5>
                <a href="<?= BASE_URL ?>history.php" class="btn btn-info btn-lg w-100 mt-3">See Past Orders</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>