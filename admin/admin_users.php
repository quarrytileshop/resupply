<?php
/**
 * resupply - Admin Users Page (Professional Rewrite)
 * Manage all users across organizations (super-admin only)
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_super_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Manage Users';

require_once '../includes/header.php';

$stmt = $pdo->query("
    SELECT u.*, o.name as org_name 
    FROM users u 
    LEFT JOIN organizations o ON u.organization_id = o.id 
    ORDER BY u.email
");
$users = $stmt->fetchAll();
?>

<h1 class="mb-4">Users</h1>

<a href="<?= BASE_URL ?>admin/invite_user.php" class="btn btn-success mb-4">+ Invite New User</a>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Organization</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
                <td><?= htmlspecialchars($user['org_name'] ?? '—') ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/admin_impersonate.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Impersonate</a>
                    <a href="<?= BASE_URL ?>admin/admin_edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>