<?php
/**
 * resupply - Approve Vendor Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Approve Vendors - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $vendor_id = (int)$_GET['approve'];

    $stmt = $pdo->prepare("UPDATE vendors SET approved = 1, approval_date = NOW() WHERE id = :id");
    $success = $stmt->execute(['id' => $vendor_id]);

    if ($success) {
        $_SESSION['message'] = "Vendor approved successfully!";
        header("Location: approve_vendor.php");
        exit;
    } else {
        $error = "Failed to approve vendor.";
    }
}

// Fetch pending vendors (preserves original logic)
$stmt = $pdo->prepare("SELECT * FROM vendors WHERE approved = 0 ORDER BY created_at");
$stmt->execute();
$pending_vendors = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Approve Pending Vendors</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($pending_vendors): ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Vendor Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Requested On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_vendors as $vendor): ?>
                            <tr>
                                <td><?= htmlspecialchars($vendor['name'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($vendor['email'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($vendor['company'] ?? '—') ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($vendor['created_at'])) ?></td>
                                <td>
                                    <a href="?approve=<?= $vendor['id'] ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Approve this vendor?');">Approve</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No pending vendor approvals at this time.</div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>