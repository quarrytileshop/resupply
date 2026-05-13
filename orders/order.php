<?php
// order.php – Updated 2026-05-11 to use header + footer
$page_title = "New Order - Resupply Rocket";
require_once 'header.php';

$is_propane = $_SESSION['is_propane'] ?? 0;
?>

<div class="container mt-4">
    <h1>New Order</h1>
    <p class="text-muted">Choose the type of order you want to place</p>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="general_order.php" class="text-decoration-none">
                <div class="card h-100 text-center p-5 border-primary">
                    <h4>General Products</h4>
                    <p class="text-muted">Shopping lists, catalog items, manual entries</p>
                </div>
            </a>
        </div>
        <?php if ($is_propane): ?>
        <div class="col-md-4">
            <a href="propane_order.php" class="text-decoration-none">
                <div class="card h-100 text-center p-5 border-warning">
                    <h4>Propane</h4>
                    <p class="text-muted">Tank exchanges &amp; new fills</p>
                </div>
            </a>
        </div>
        <?php endif; ?>
        <div class="col-md-4">
            <a href="paint_order.php" class="text-decoration-none">
                <div class="card h-100 text-center p-5 border-info">
                    <h4>Paint Order</h4>
                    <p class="text-muted">Guided color &amp; finish selection</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="checkbox_create.php" class="text-decoration-none">
                <div class="card h-100 text-center p-5 border-secondary">
                    <h4>Checkbox List</h4>
                    <p class="text-muted">In-store or team checklist</p>
                </div>
            </a>
        </div>
    </div>

    <div class="mt-5">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
