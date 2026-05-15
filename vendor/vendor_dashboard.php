<?php
/**
 * resupply - Vendor Dashboard
 * Updated for new folder structure (May 14, 2026)
 * Removed duplicate container (header.php already provides it)
 * Clean single-layer layout - no more background rectangles
 */

$page_title = "Vendor Dashboard - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_vendor()) {
    header("Location: ../login.php");
    exit;
}
?>

<h1 class="mb-4">Vendor Dashboard</h1>
<p class="lead">Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Vendor') ?>!</p>

<div class="alert alert-info">
    <strong>Note:</strong> Full stats and recent orders will be added once we confirm the exact database structure.
</div>

<div class="row g-4 mb-5">
    <!-- My Organizations -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <img src="/assets/icons/general.png" alt="Organizations" style="width:64px;height:64px;" class="mb-3">
                <h5 class="card-title">My Organizations</h5>
                <p class="card-text text-muted">Manage assigned companies</p>
                <a href="vendor_organizations.php" class="btn btn-primary w-100 mt-auto">Manage Organizations</a>
            </div>
        </div>
    </div>

    <!-- Shopping Lists -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <img src="/assets/icons/paint.png" alt="Shopping Lists" style="width:64px;height:64px;" class="mb-3">
                <h5 class="card-title">Shopping Lists</h5>
                <p class="card-text text-muted">View and assign lists</p>
                <a href="vendor_shopping_lists.php" class="btn btn-success w-100 mt-auto">View Lists</a>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center p-4">
                <img src="/assets/icons/propane.png" alt="Reports" style="width:64px;height:64px;" class="mb-3">
                <h5 class="card-title">Reports</h5>
                <p class="card-text text-muted">Usage and billing reports</p>
                <a href="#" class="btn btn-warning w-100 mt-auto text-dark">Usage Reports (coming soon)</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <a href="../dashboard.php" class="btn btn-secondary">Back to Main Dashboard</a>
</div>

<?php require_once '../includes/footer.php'; ?>