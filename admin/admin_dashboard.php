<?php
/**
 * resupply - Admin Dashboard (Robust Billing Version)
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_super_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Admin Dashboard';

require_once '../includes/header.php';
?>

<h1 class="mb-4">Super Admin Dashboard</h1>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5>Billing This Month</h5>
                <a href="<?= BASE_URL ?>admin/billing_report.php" class="btn btn-danger btn-lg w-100 mt-3">Generate Full Billing Report</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>