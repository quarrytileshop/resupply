<?php
/**
 * resupply - Admin Delete Vendor Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Delete Vendor - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

$vendor_id = (int)($_GET['id'] ?? 0);

if ($vendor_id > 0 && isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Delete vendor (preserves original behavior - soft delete or hard delete depending on your schema)
    $stmt = $pdo->prepare("DELETE FROM vendors WHERE id = :id");
    $success = $stmt->execute(['id' => $vendor_id]);

    if ($success) {
        $_SESSION['message'] = "Vendor deleted successfully.";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Failed to delete vendor.";
    }
}

// Fetch vendor details for confirmation
if ($vendor_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM vendors WHERE id = :id");
    $stmt->execute(['id' => $vendor_id]);
    $vendor = $stmt->fetch();
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Delete Vendor</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($vendor)): ?>
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="text-danger">Are you sure you want to delete this vendor?</h5>
                <p><strong>Name:</strong> <?= htmlspecialchars($vendor['name']) ?></p>
                <p><strong>Company:</strong> <?= htmlspecialchars($vendor['company'] ?? '—') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($vendor['email']) ?></p>

                <div class="mt-4">
                    <a href="?id=<?= $vendor_id ?>&confirm=yes" 
                       class="btn btn-danger btn-lg px-5"
                       onclick="return confirm('This action cannot be undone. Delete this vendor?');">
                        YES — Delete Vendor
                    </a>
                    <a href="admin_dashboard.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Vendor not found.</div>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Admin Dashboard</a>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>