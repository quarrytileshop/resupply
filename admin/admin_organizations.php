<?php
/**
 * resupply - Admin Organizations Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Manage Organizations - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Fetch all organizations (preserves original logic)
$stmt = $pdo->prepare("SELECT * FROM organizations ORDER BY name");
$stmt->execute();
$organizations = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Organizations</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="admin_pre_register.php" class="btn btn-success">Add New Organization</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($organizations): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Organization Name</th>
                                <th>Contact Email</th>
                                <th>Address</th>
                                <th>Users</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['contact_email'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($org['address'] ?? '—') ?></td>
                                <td>
                                    <?php
                                    $userCount = $pdo->prepare("SELECT COUNT(*) FROM users WHERE organization_id = ?");
                                    $userCount->execute([$org['id']]);
                                    echo $userCount->fetchColumn();
                                    ?>
                                </td>
                                <td>
                                    <a href="admin_organization_catalog.php?org_id=<?= $org['id'] ?>" class="btn btn-sm btn-outline-primary">Catalog</a>
                                    <a href="admin_users.php" class="btn btn-sm btn-outline-secondary">Users</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No organizations found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>