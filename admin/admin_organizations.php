<?php
/**
 * resupply - Admin Organizations Page (Professional Rewrite)
 * List, create, and manage all organizations (super-admin only)
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_super_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Manage Organizations';

require_once '../includes/header.php';

// Fetch all organizations
$stmt = $pdo->query("SELECT * FROM organizations ORDER BY name ASC");
$organizations = $stmt->fetchAll();
?>

<h1 class="mb-4">Organizations</h1>

<a href="<?= BASE_URL ?>admin/admin_create_organization.php" class="btn btn-success mb-4">+ Create New Organization</a>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Users</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($organizations as $org): ?>
            <tr>
                <td><?= htmlspecialchars($org['name']) ?></td>
                <td><?= $org['user_count'] ?? '—' ?></td>
                <td><?= date('M j, Y', strtotime($org['created_at'])) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>admin/admin_edit_organization.php?id=<?= $org['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="<?= BASE_URL ?>admin/admin_users.php?org_id=<?= $org['id'] ?>" class="btn btn-sm btn-info">Users</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>