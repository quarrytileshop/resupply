<?php
/**
 * resupply - Admin Shopping Lists Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Manage Shopping Lists - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Fetch all shopping lists (preserves original logic)
$stmt = $pdo->prepare("SELECT sl.*, o.name as org_name 
                       FROM shopping_lists sl 
                       LEFT JOIN organizations o ON sl.organization_id = o.id 
                       ORDER BY sl.created_at DESC");
$stmt->execute();
$lists = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Shopping Lists</h1>
    <p class="text-muted">All shopping lists created by organizations and vendors.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if ($lists): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>List Name</th>
                                <th>Organization</th>
                                <th>Created</th>
                                <th>Items</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $list): ?>
                            <tr>
                                <td><?= htmlspecialchars($list['name']) ?></td>
                                <td><?= htmlspecialchars($list['org_name'] ?? '—') ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($list['created_at'])) ?></td>
                                <td><?= $list['item_count'] ?? '—' ?></td>
                                <td>
                                    <a href="../shopping_lists.php?list_id=<?= $list['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No shopping lists found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>