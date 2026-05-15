<?php
/**
 * resupply - Organization Admin Page (Professional Rewrite)
 * Org-admin only. Manage their own organization’s users and settings.
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

if (!is_org_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Organization Admin';

require_once 'includes/header.php';

// Fetch org users (scoped to current organization)
$stmt = $pdo->prepare("SELECT * FROM users WHERE organization_id = ? ORDER BY email");
$stmt->execute([$_SESSION['organization_id']]);
$orgUsers = $stmt->fetchAll();
?>

<h1 class="mb-4">Organization Administration</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5>Manage Your Team</h5>
        <a href="<?= BASE_URL ?>admin/invite_user.php" class="btn btn-success">Invite New User to Organization</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orgUsers as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>edit_profile.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>