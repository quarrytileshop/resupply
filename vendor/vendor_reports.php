<?php
/**
 * resupply - Vendor Reports Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Vendor Reports - Resupply Rocket";
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

// Fetch usage / order report data for this vendor (preserves original logic)
$stmt = $pdo->prepare("SELECT o.*, org.name as org_name, COUNT(oi.id) as item_count 
                       FROM orders o 
                       LEFT JOIN organizations org ON o.organization_id = org.id 
                       LEFT JOIN order_items oi ON oi.order_id = o.id 
                       WHERE o.vendor_id = :vendor_id 
                       GROUP BY o.id 
                       ORDER BY o.created_at DESC LIMIT 50");
$stmt->execute(['vendor_id' => $vendor_id]);
$reports = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Vendor Reports</h1>
    <p class="text-muted">Usage, order history, and activity reports for your organizations.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Recent Order Activity</h5>
        </div>
        <div class="card-body">
            <?php if ($reports): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Organization</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= htmlspecialchars($report['id']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($report['created_at'])) ?></td>
                                <td><?= htmlspecialchars($report['org_name'] ?? '—') ?></td>
                                <td><?= $report['item_count'] ?></td>
                                <td><span class="badge bg-<?= $report['status'] === 'sent' ? 'success' : 'warning' ?>"><?= ucfirst($report['status'] ?? 'Pending') ?></span></td>
                                <td>
                                    <a href="../orders/view_order.php?id=<?= $report['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No orders found yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Usage Report Link (original feature preserved) -->
    <div class="card">
        <div class="card-body text-center">
            <h5>Usage Report</h5>
            <p class="text-muted">See detailed usage logs from your organizations</p>
            <a href="vendor_usage_report.php" class="btn btn-info btn-lg px-5">View Full Usage Report</a>
        </div>
    </div>

    <div class="mt-5">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>