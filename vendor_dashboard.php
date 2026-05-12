<?php
// vendor_dashboard.php – Fixed to allow superadmin access for testing – 2026-05-11
$page_title = "Vendor Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Allow BOTH vendor admins AND superadmin to view this page
if (!isset($_SESSION['is_organization_admin']) && !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Stats for this vendor
$total_customers = $pdo->query("SELECT COUNT(*) FROM organizations WHERE vendor_id = " . (int)$vendor_id)->fetchColumn();
$total_lists = $pdo->query("SELECT COUNT(*) FROM shopping_lists WHERE vendor_id = " . (int)$vendor_id)->fetchColumn();
$total_catalog = $pdo->query("SELECT COUNT(*) FROM catalog_items WHERE vendor_id = " . (int)$vendor_id . " OR vendor_id IS NULL")->fetchColumn();
?>

<div class="container mt-4">
    <h1 class="mb-3">Vendor Dashboard</h1>
    <p class="text-muted">Welcome! You are now in the isolated vendor area.</p>

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
                    <h5>Catalog Items</h5>
                    <h2 class="text-teal"><?= $total_catalog ?></h2>
                    <a href="admin_catalog.php" class="btn btn-primary mt-3">Manage Catalog →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Regular Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
