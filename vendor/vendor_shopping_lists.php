<?php
/**
 * resupply - Vendor Shopping Lists Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Vendor Shopping Lists - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_vendor()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Fetch shopping lists for organizations assigned to this vendor
$stmt = $pdo->prepare("SELECT sl.*, o.name as org_name 
                       FROM shopping_lists sl 
                       JOIN organizations o ON sl.organization_id = o.id 
                       WHERE o.vendor_id = :vendor_id 
                       ORDER BY sl.created_at DESC");
$stmt->execute(['vendor_id' => $vendor_id]);
$lists = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Shopping Lists</h1>
    <p class="text-muted">All shopping lists created by your partnered organizations.</p>

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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $list): ?>
                            <tr>
                                <td><?= htmlspecialchars($list['name']) ?></td>
                                <td><?= htmlspecialchars($list['org_name']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($list['created_at'])) ?></td>
                                <td>
                                    <a href="../shopping_lists.php?list_id=<?= $list['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary">View List</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No shopping lists yet. When your organizations create lists, they will appear here.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-5">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>