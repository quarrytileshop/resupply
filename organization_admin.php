<?php
/**
 * resupply - Organization Admin Page
 * Updated for new folder structure (May 14, 2026)
 * All includes, asset paths, and internal links updated
 */

$page_title = "Organization Admin - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in() || !is_org_admin()) {
    header("Location: login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

$organization_id = $_SESSION['organization_id'] ?? 0;

// Fetch organization details (preserves original logic)
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = :id");
$stmt->execute(['id' => $organization_id]);
$org = $stmt->fetch();
?>

<div class="container mt-4">
    <h1 class="mb-4">Organization Admin</h1>
    <p class="text-muted">Manage your organization's settings, users, and vendor relationships.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Organization Info Card -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Organization Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?= htmlspecialchars($org['name'] ?? 'Not set') ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($org['address'] ?? 'Not set') ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($org['contact_email'] ?? 'Not set') ?></p>
                    <a href="admin/admin_organizations.php" class="btn btn-outline-primary w-100">Edit Organization Details</a>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="shopping_list_builder.php" class="btn btn-success">Build New Shopping List</a>
                        <a href="orders/order.php" class="btn btn-primary">Create New Order</a>
                        <a href="vendor/vendor_invite_org.php" class="btn btn-info">Invite Vendor</a>
                        <a href="admin/admin_users.php" class="btn btn-warning">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Users -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Organization Users</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE organization_id = :org_id ORDER BY first_name");
                    $stmt->execute(['org_id' => $organization_id]);
                    $users = $stmt->fetchAll();
                    ?>
                    <?php if ($users): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                                        <td><?= htmlspecialchars($u['email']) ?></td>
                                        <td><?= $u['is_organization_admin'] ? '<span class="badge bg-success">Org Admin</span>' : 'User' ?></td>
                                        <td><?= $u['approval_status'] === 'approved' ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning">Pending</span>' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No users yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>