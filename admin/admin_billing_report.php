<?php
/**
 * resupply - Admin Billing Report Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Billing Report - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Example billing report query (preserves original logic - adjust to your actual schema)
$stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, org.name as org_name 
                       FROM orders o 
                       LEFT JOIN users u ON o.user_id = u.id 
                       LEFT JOIN organizations org ON o.organization_id = org.id 
                       ORDER BY o.created_at DESC LIMIT 100");
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Billing Report</h1>
    <p class="text-muted">Overview of all orders for billing purposes.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if ($orders): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Organization</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td><?= htmlspecialchars($order['org_name'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($order['order_type'] ?? 'General')) ?></td>
                                <td class="text-end">$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                                <td><span class="badge bg-<?= $order['status'] === 'sent' ? 'success' : 'warning' ?>"><?= ucfirst($order['status'] ?? 'Pending') ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No orders found for the billing report.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
