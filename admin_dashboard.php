<?php
// admin_dashboard.php – Full version with All Active Vendors list – 2026-05-11
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
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email 
                       FROM users 
                       WHERE is_organization_admin = 1 AND approval_status = 'pending' 
                       ORDER BY id DESC");
$stmt->execute();
$pending_vendors = $stmt->fetchAll();

// ALL ACTIVE VENDORS (new section)
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, approval_status 
                       FROM users 
                       WHERE is_organization_admin = 1 AND approval_status = 'approved' 
                       ORDER BY id DESC");
$stmt->execute();
$active_vendors = $stmt->fetchAll();
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

    <!-- Pending Vendor Applications -->
    <div class="card mb-5">
        <div class="card-header bg-warning text-dark">
            <h5>Pending Vendor Applications (<?= count($pending_vendors) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pending_vendors)): ?>
                <p class="text-muted">No pending vendor applications at this time.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_vendors as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['first_name'] . ' ' . $v['last_name']) ?></td>
                                <td><?= htmlspecialchars($v['email']) ?></td>
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

    <!-- ALL ACTIVE VENDORS (new section) -->
    <div class="card mb-5">
        <div class="card-header bg-success text-white">
            <h5>All Active Vendors (<?= count($active_vendors) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($active_vendors)): ?>
                <p class="text-muted">No active vendors yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_vendors as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['first_name'] . ' ' . $v['last_name']) ?></td>
                                <td><?= htmlspecialchars($v['email']) ?></td>
                                <td><span class="badge bg-success">Approved</span></td>
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
                <a href="vendor_register.php" class="btn btn-outline-secondary">View Public Vendor Signup</a>
                <a href="admin_create_vendor.php" class="btn btn-success">Manually Create Vendor</a>
                <a href="admin_assign_organization_to_vendor.php" class="btn btn-primary">Assign Organization to Vendor</a>
                <a href="admin_organizations.php" class="btn btn-outline-primary">Manage All Organizations</a>
                <a href="admin_users.php" class="btn btn-outline-primary">Manage All Users</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
