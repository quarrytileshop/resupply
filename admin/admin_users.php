<?php
/**
 * resupply - Admin Users Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Manage Users - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Fetch all users (preserves original logic)
$stmt = $pdo->prepare("SELECT u.*, o.name as org_name 
                       FROM users u 
                       LEFT JOIN organizations o ON u.organization_id = o.id 
                       ORDER BY u.first_name, u.last_name");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Users</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="admin_pre_register.php" class="btn btn-success">Pre-Register New User</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($users): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Organization</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['org_name'] ?? '—') ?></td>
                                <td>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="badge bg-danger">Super Admin</span>
                                    <?php elseif ($user['is_vendor_admin']): ?>
                                        <span class="badge bg-warning">Vendor</span>
                                    <?php elseif ($user['is_organization_admin']): ?>
                                        <span class="badge bg-info">Org Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">User</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['approval_status'] === 'approved' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($user['approval_status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="admin_impersonate.php?user_id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Impersonate</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No users found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>