<?php
/**
 * resupply - Admin Dashboard (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Admin Dashboard - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

// Quick stats (preserves original logic - adjust queries to your schema)
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orgs  = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM users WHERE approval_status = 'pending'")->fetchColumn();
?>

<div class="container mt-4">
    <h1 class="mb-4">Admin Dashboard</h1>
    <p class="text-muted">Super-admin overview and quick actions.</p>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3 class="text-primary"><?= $total_users ?></h3>
                    <p class="text-muted">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="text-success"><?= $total_orgs ?></h3>
                    <p class="text-muted">Organizations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3 class="text-info"><?= $total_orders ?></h3>
                    <p class="text-muted">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning"><?= $pending_approvals ?></h3>
                    <p class="text-muted">Pending Approvals</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Links -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Management</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="admin_organizations.php" class="btn btn-outline-primary">Manage Organizations</a>
                        <a href="admin_users.php" class="btn btn-outline-primary">Manage Users</a>
                        <a href="admin_catalog.php" class="btn btn-outline-primary">Catalog Management</a>
                        <a href="admin_shopping_lists.php" class="btn btn-outline-primary">Shopping Lists</a>
                        <a href="admin_billing_report.php" class="btn btn-outline-primary">Billing Report</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Tools</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="admin_create_vendor.php" class="btn btn-outline-success">Create New Vendor</a>
                        <a href="admin_assign_organization_to_vendor.php" class="btn btn-outline-success">Assign Org to Vendor</a>
                        <a href="approve_vendor.php" class="btn btn-outline-success">Approve Vendors</a>
                        <a href="vendor/vendor_reports.php" class="btn btn-outline-success">Vendor Reports</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Utilities</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="admin_impersonate.php" class="btn btn-outline-warning">Impersonate User</a>
                        <a href="bulk_import.php" class="btn btn-outline-warning">Bulk Import</a>
                        <a href="test_db.php" class="btn btn-outline-secondary">Test Database</a>
                        <a href="phpinfo.php" class="btn btn-outline-secondary">PHP Info</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="../dashboard.php" class="btn btn-secondary">← Back to Main Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>