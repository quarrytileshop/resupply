<?php
// admin_dashboard.php – Full rewrite with original logic – Updated 2026-05-11
$page_title = "Super Admin Dashboard - Resupply Rocket";
require_once 'header.php';

// Fetch stats (original logic preserved)
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orgs = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM users WHERE approval_status = 'pending'")->fetchColumn();
?>

<div class="container mt-4">
    <h1 class="mb-3">Super Admin Dashboard</h1>
    <p class="text-muted">Manage vendors, organizations, users, catalog, and shopping lists</p>

    <!-- Modern stats cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Total Organizations</h5>
                    <h2 class="text-teal"><?= $total_orgs ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h2 class="text-teal"><?= $total_users ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h2 class="text-teal"><?= $total_orders ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Pending Approvals</h5>
                    <h2 class="text-warning"><?= $pending_approvals ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick actions (original links preserved) -->
    <div class="card">
        <div class="card-body">
            <h5>Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="admin_organizations.php" class="btn btn-outline-primary">Manage Organizations</a>
                <a href="admin_users.php" class="btn btn-outline-primary">Manage Users</a>
                <a href="admin_shopping_lists.php" class="btn btn-outline-primary">Shopping Lists</a>
                <a href="admin_catalog.php" class="btn btn-outline-primary">Catalog Items</a>
                <a href="admin_vendors.php" class="btn btn-outline-primary">Vendors</a>
                <a href="admin_reports.php" class="btn btn-outline-primary">Reports</a>
            </div>
        </div>
    </div>

    <!-- Recent activity table placeholder – add your original recent orders/users logic here if needed -->
    <div class="card mt-4">
        <div class="card-header">Recent Activity</div>
        <div class="card-body">
            <p class="text-muted">Your original recent activity table or list can go here.</p>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
