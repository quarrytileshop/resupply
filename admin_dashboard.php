<?php
// admin_dashboard.php – Full expanded version with Pending Vendor Approvals section – 2026-05-11
$page_title = "Super Admin Dashboard - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: dashboard.php");
    exit;
}

// Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orgs = $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_approvals = $pdo->query("SELECT COUNT(*) FROM users WHERE approval_status = 'pending'")->fetchColumn();
$total_vendors = $pdo->query("SELECT COUNT(*) FROM users WHERE is_organization_admin = 1")->fetchColumn();

// Pending vendor applications
$stmt = $pdo->query("SELECT id, first_name, last_name, email, created_at 
                     FROM users 
                     WHERE is_organization_admin = 1 AND approval_status = 'pending' 
                     ORDER BY created_at DESC");
$pending_vendors = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Super Admin Dashboard</h1>
    <p class="text-muted">Manage everything across all vendors, organizations, users, and catalog items.</p>

    <!-- Stats -->
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
    </div>

    <!-- Pending Vendor Approvals -->
    <div class="card mb-5">
        <div class="card-header bg-warning text-dark">
            <h5>Pending Vendor Applications (<?= count($pending_vendors) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pending_vendors)): ?>
                <p class="text-muted">No pending vendor applications.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Applied On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_vendors as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['first_name'] . ' ' . $v['last_name']) ?></td>
                                <td><?= htmlspecialchars($v['email']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($v['created_at'])) ?></td>
                                <td>
                                    <a href="approve_vendor.php?id=<?= $v['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-body">
            <h5>Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="vendor_register.php" class="btn btn-outline-secondary">View Public Vendor Signup Page</a>
                <a href="admin_create_vendor.php" class="btn btn-success">Manually Create Vendor</a>
                <a href="admin_assign_organization_to_vendor.php" class="btn btn-primary">Assign Org to Vendor</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
