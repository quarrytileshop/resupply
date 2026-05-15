<?php
/**
 * resupply - Vendor Dashboard (Robust Billing Version)
 * Shows billable organizations count
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_vendor()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Vendor Dashboard';

require_once '../includes/header.php';

$billableCount = getBillableOrganizationsCount($_SESSION['vendor_id'] ?? 0);
?>

<h1 class="mb-4">Vendor Dashboard</h1>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Billable Organizations This Month</h5>
                <h2 class="text-warning"><?= $billableCount ?></h2>
                <p class="text-muted">Beyond your 2 free slots</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5>Total Organizations Served</h5>
                <h2 class="text-primary"><?= count($orgs ?? []) ?></h2> <!-- will be populated in future -->
            </div>
        </div>
    </div>
</div>

<a href="<?= BASE_URL ?>vendor/vendor_organizations.php" class="btn btn-primary mt-4">Monitor Organizations &amp; Usage</a>

<?php require_once '../includes/footer.php'; ?>