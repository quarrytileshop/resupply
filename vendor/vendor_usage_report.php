<?php
/**
 * resupply - Vendor Usage Report Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Usage Report - Resupply Rocket";
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

// Fetch usage report data for organizations assigned to this vendor
$stmt = $pdo->prepare("SELECT 
    u.first_name, u.last_name,
    org.name as org_name,
    p.name as product_name,
    ul.quantity,
    ul.notes,
    ul.recorded_at
    FROM usage_logs ul
    JOIN users u ON ul.user_id = u.id
    JOIN organizations org ON ul.organization_id = org.id
    JOIN products p ON ul.product_id = p.id
    WHERE org.vendor_id = :vendor_id
    ORDER BY ul.recorded_at DESC LIMIT 100");
$stmt->execute(['vendor_id' => $vendor_id]);
$usage_logs = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Usage Report</h1>
    <p class="text-muted">Detailed usage logs from all your partnered organizations.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if ($usage_logs): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Organization</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Quantity Used</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usage_logs as $log): ?>
                            <tr>
                                <td><?= date('M j, Y g:i A', strtotime($log['recorded_at'])) ?></td>
                                <td><?= htmlspecialchars($log['org_name']) ?></td>
                                <td><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></td>
                                <td><?= htmlspecialchars($log['product_name']) ?></td>
                                <td class="text-end"><?= (int)$log['quantity'] ?></td>
                                <td><?= htmlspecialchars($log['notes'] ?? '—') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No usage logs recorded yet. When your organizations record usage, it will appear here.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-5">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>