<?php
// admin_dashboard.php – Full expanded version with complete stats cards and actions – 2026-05-11
$page_title = "Super Admin Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: dashboard.php");
    exit;
}

// Fetch real stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orgs = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM users WHERE approval_status = 'pending'")->fetchColumn();
$total_vendors = $pdo->query("SELECT COUNT(*) FROM users WHERE is_organization_admin = 1")->fetchColumn();
?>
<div class="container mt-4">
    <h1 class="mb-3">Super Admin Dashboard</h1>
    <p class="text-muted">Manage everything across all vendors, organizations, users, and catalog items.</p>

    <!-- Full stats cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Organizations</h5>
                    <h2 class="text-teal display-4"><?= $total_orgs ?></h2>
                    <a href="admin_organizations.php" class="btn btn-outline-primary mt-3">Manage Organizations</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Users</h5>
                    <h2 class="text-teal display-4"><?= $total_users ?></h2>
                    <a href="admin_users.php" class="btn btn-outline-primary mt-3">Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Orders</h5>
                    <h2 class="text-teal display-4"><?= $total_orders ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Pending Approvals</h5>
                    <h2 class="text-warning display-4"><?= $pending_approvals ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Active Vendors</h5>
                    <h2 class="text-teal display-4"><?= $total_vendors ?></h2>
                    <a href="admin_create_vendor.php" class="btn btn-success mt-3">Create New Vendor</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Assign Organizations</h5>
                    <a href="admin_assign_organization_to_vendor.php" class="btn btn-primary mt-3">Assign to Vendor</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h5>Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="admin_create_vendor.php" class="btn btn-success">+ Create New Vendor Admin</a>
                <a href="admin_assign_organization_to_vendor.php" class="btn btn-primary">Assign Organization to Vendor</a>
                <a href="admin_organizations.php" class="btn btn-outline-primary">Manage All Organizations</a>
                <a href="admin_users.php" class="btn btn-outline-primary">Manage All Users</a>
                <a href="admin_catalog.php" class="btn btn-outline-primary">Manage Catalog Items</a>
                <a href="vendor_reports.php" class="btn btn-outline-primary">View Reports</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity placeholder (you can expand later) -->
    <div class="card mt-5">
        <div class="card-header">Recent Activity</div>
        <div class="card-body">
            <p class="text-muted">Recent orders and user registrations will appear here in a future update.</p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
