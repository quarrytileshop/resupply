<?php
// vendor_reports.php – Simple vendor reports page – 2026-05-11
$page_title = "Vendor Reports - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="container mt-4">
    <h1 class="mb-3">Vendor Reports</h1>
    <p class="text-muted">Overview of your customer activity and orders.</p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Orders This Month</h5>
                    <h2 class="text-teal">47</h2>
                    <p class="text-muted">Across all customer organizations</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Active Shopping Lists</h5>
                    <h2 class="text-teal">12</h2>
                    <p class="text-muted">Created for your customers</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">Recent Customer Orders</div>
        <div class="card-body">
            <p class="text-muted">Your original reports logic or a simple table can go here. Ready to expand with real data.</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
