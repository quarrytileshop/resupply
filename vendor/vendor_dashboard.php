<?php
/**
 * resupply - Vendor Dashboard (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Vendor Dashboard - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_vendor()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Quick stats for this vendor (preserves original logic)
$vendor_id = $_SESSION['vendor_id'] ?? 0;

$total_orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE vendor_id = :vid");
$total_orders->execute(['vid' => $vendor_id]);
$total_orders = $total_orders->fetchColumn();

$pending_orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE vendor_id = :vid AND status != 'sent'");
$pending_orders->execute(['vid' => $vendor_id]);
$pending_orders = $pending_orders->fetchColumn();
?>

<div class="container mt-4">
    <h1 class="mb-4">Vendor Dashboard</h1>
    <p class="lead text-muted">Welcome back! Here's your resupply overview.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3 class="text-primary"><?= $total_orders ?></h3>
                    <p class="text-muted">Total Orders Received</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning"><?= $pending_orders ?></h3>
                    <p class="text-muted">Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="text-success">0</h3>
                    <p class="text-muted">Open Shopping Lists</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="vendor_organizations.php" class="btn btn-outline-primary">My Organizations</a>
                        <a href="vendor_shopping_lists.php" class="btn btn-outline-primary">Shopping Lists</a>
                        <a href="vendor_reports.php" class="btn btn-outline-primary">Usage Reports</a>
                        <a href="vendor_invite_org.php" class="btn btn-outline-success">Invite New Org</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-md-6 col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM orders 
                                           WHERE vendor_id = :vid 
                                           ORDER BY created_at DESC LIMIT 5");
                    $stmt->execute(['vid' => $vendor_id]);
                    $recent = $stmt->fetchAll();
                    ?>
                    <?php if ($recent): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Organization</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['id']) ?></td>
                                        <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($order['organization_name'] ?? '—') ?></td>
                                        <td><span class="badge bg-<?= $order['status'] === 'sent' ? 'success' : 'warning' ?>"><?= ucfirst($order['status'] ?? 'Pending') ?></span></td>
                                        <td><a href="../orders/view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No recent orders yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="../dashboard.php" class="btn btn-secondary">← Back to Main Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>