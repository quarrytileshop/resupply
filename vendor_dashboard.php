<?php
// vendor_dashboard.php – Added Usage Report link – 2026-05-12
$page_title = "Vendor Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Stats
$total_customers = $pdo->query("SELECT COUNT(*) FROM organizations WHERE vendor_id = " . (int)$vendor_id)->fetchColumn() ?? 0;
$total_lists     = $pdo->query("SELECT COUNT(*) FROM shopping_lists WHERE vendor_id = " . (int)$vendor_id)->fetchColumn() ?? 0;
$total_catalog   = $pdo->query("SELECT COUNT(*) FROM catalog_items WHERE vendor_id = " . (int)$vendor_id . " OR vendor_id IS NULL")->fetchColumn() ?? 0;
?>

<div class="container mt-4">
    <h1 class="mb-3">Vendor Dashboard</h1>
    <p class="text-muted">Welcome! Manage your customers and track usage.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>My Customer Organizations</h5>
                    <h2 class="text-teal"><?= $total_customers ?></h2>
                    <a href="vendor_organizations.php" class="btn btn-primary mt-3">Manage Customers →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Shopping Lists</h5>
                    <h2 class="text-teal"><?= $total_lists ?></h2>
                    <a href="shopping_list_builder.php" class="btn btn-primary mt-3">Build Lists →</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Usage Report</h5>
                    <h2 class="text-teal">View</h2>
                    <a href="vendor_usage_report.php" class="btn btn-info mt-3">See Monthly Usage →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Regular Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
