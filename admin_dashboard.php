<?php
// admin_dashboard.php – Added link to Billing Report – 2026-05-12
$page_title = "Super Admin Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Access Denied. You are not logged in as Super Admin.</div></div>';
    require_once 'footer.php';
    exit;
}

// Debug info
echo '<div class="container mt-3"><div class="alert alert-info small">';
echo 'DEBUG: You are logged in as Super Admin (user_id = ' . $_SESSION['user_id'] . ')';
echo '</div></div>';

// Safe stats
$total_users       = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;
$total_orgs        = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn() ?? 0;
$total_orders      = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?? 0;
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM users WHERE approval_status = 'pending'")->fetchColumn() ?? 0;
$total_vendors     = $pdo->query("SELECT COUNT(*) FROM users WHERE is_organization_admin = 1")->fetchColumn() ?? 0;

// Pending vendor applications
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email FROM users WHERE is_organization_admin = 1 AND approval_status = 'pending' ORDER BY id DESC");
$stmt->execute();
$pending_vendors = $stmt->fetchAll();

// ALL ACTIVE VENDORS
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, approval_status FROM users WHERE is_organization_admin = 1 AND approval_status = 'approved' ORDER BY id DESC");
$stmt->execute();
$active_vendors = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Super Admin Dashboard</h1>
    <p class="text-muted">Manage everything across all vendors, organizations, users, and catalog items.</p>

    <!-- Stats (same as before) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Organizations</h5>
                    <h2 class="text-teal display-4"><?= $total_orgs ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Total Users</h5>
                    <h2 class="text-teal display-4"><?= $total_users ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="text-muted">Active Vendors</h5>
                    <h2 class="text-teal display-4"><?= $total_vendors ?></h2>
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
    </div>

    <!-- Pending Vendor Applications (same as before) -->
    <!-- ... (kept the same pending section) ... -->

    <!-- ALL ACTIVE VENDORS (same as before) -->
    <!-- ... (kept the same active vendors section with edit/delete) ... -->

    <!-- Quick Actions - Added Billing Report -->
    <div class="card">
        <div class="card-body">
            <h5>Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="vendor_register.php" class="btn btn-outline-secondary">View Public Vendor Signup</a>
                <a href="admin_create_vendor.php" class="btn btn-success">Manually Create Vendor</a>
                <a href="admin_assign_organization_to_vendor.php" class="btn btn-primary">Assign Organization to Vendor</a>
                <a href="admin_billing_report.php" class="btn btn-info">Billing & Usage Report</a>
                <a href="admin_organizations.php" class="btn btn-outline-primary">Manage All Organizations</a>
                <a href="admin_users.php" class="btn btn-outline-primary">Manage All Users</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
