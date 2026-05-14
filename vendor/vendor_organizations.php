<?php
/**
 * resupply - Vendor Organizations Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "My Organizations - Resupply Rocket";
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

// Fetch organizations assigned to this vendor (preserves original logic)
$stmt = $pdo->prepare("SELECT o.*, COUNT(u.id) as user_count 
                       FROM organizations o 
                       LEFT JOIN users u ON u.organization_id = o.id 
                       WHERE o.vendor_id = :vendor_id 
                       GROUP BY o.id 
                       ORDER BY o.name");
$stmt->execute(['vendor_id' => $vendor_id]);
$organizations = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">My Organizations</h1>
    <p class="text-muted">Organizations you are partnered with for resupply.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="vendor_invite_org.php" class="btn btn-success">+ Invite New Organization</a>
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
                                <th>Users</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['contact_email'] ?? '—') ?></td>
                                <td><?= $org['user_count'] ?></td>
                                <td>
                                    <a href="../shopping_lists.php?org_id=<?= $org['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary">Shopping Lists</a>
                                    <a href="../orders/order.php?org_id=<?= $org['id'] ?>" 
                                       class="btn btn-sm btn-outline-success">New Order</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    You are not yet assigned to any organizations.<br>
                    Use the "Invite New Organization" button above to get started.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-5">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>