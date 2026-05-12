<?php
// vendor_dashboard.php – Updated with prominent My Customers card – 2026-05-12
$page_title = "Vendor Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

$total_customers = $pdo->query("SELECT COUNT(*) FROM organizations WHERE vendor_id = " . (int)$vendor_id)->fetchColumn() ?? 0;
$total_lists     = $pdo->query("SELECT COUNT(*) FROM shopping_lists WHERE vendor_id = " . (int)$vendor_id)->fetchColumn() ?? 0;
?>

<div class="container mt-4">
    <h1 class="mb-3">Vendor Dashboard</h1>
    <p class="text-muted">Welcome! Manage your customers, shopping lists, and usage.</p>

    <div class="row g-4">
        <!-- Prominent My Customers card -->
        <div class="col-md-6">
            <div class="card text-center h-100 border-primary">
                <div class="card-body">
                    <h5>My Customers</h5>
                    <h2 class="text-teal"><?= $total_customers ?></h2>
                    <p class="text-muted">Add organizations, approve them, and send invite links</p>
                    <a href="vendor_organizations.php" class="btn btn-primary btn-lg mt-3">Manage Customers &amp; Send Invites →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Shopping Lists</h5>
                    <h2 class="text-teal"><?= $total_lists ?></h2>
                    <a href="shopping_list_builder.php" class="btn btn-primary btn-lg mt-3">Build Shopping Lists →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Usage Report</h5>
                    <a href="vendor_usage_report.php" class="btn btn-info btn-lg mt-3">View Monthly Usage →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Regular Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
