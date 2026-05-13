<?php
// vendor_usage_report.php – Vendor's own usage report (billing switched off) – 2026-05-12
$page_title = "My Usage Report - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: vendor_dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Get usage for this vendor's organizations this month and last month
$current_month = date('Y-m-01');
$last_month    = date('Y-m-01', strtotime('-1 month'));

$stmt = $pdo->prepare("
    SELECT o.name as org_name, 
           COALESCE(SUM(mu.orders_count), 0) as orders_count,
           COALESCE(SUM(mu.checkbox_uses), 0) as checkbox_uses,
           COUNT(DISTINCT mu.organization_id) as active_orgs
    FROM organizations o
    LEFT JOIN monthly_usage mu ON o.id = mu.organization_id 
        AND mu.usage_month = :month
    WHERE o.vendor_id = :vendor_id
    GROUP BY o.id
    ORDER BY o.name
");
$stmt->execute(['month' => $current_month, 'vendor_id' => $vendor_id]);
$current_usage = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">My Usage Report</h1>
    <p class="text-muted">Active organizations = those that placed orders from shopping lists OR used checkbox lists this month.</p>

    <div class="card">
        <div class="card-body">
            <h5>Current Month (<?= date('F Y') ?>)</h5>
            <?php if (empty($current_usage)): ?>
                <p class="text-muted">No activity yet this month.</p>
            <?php else: ?>
                <table class="table table-hover">
                    <thead><tr><th>Organization</th><th>Orders</th><th>Checkbox Uses</th><th>Active</th></tr></thead>
                    <tbody>
                        <?php foreach ($current_usage as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['org_name']) ?></td>
                            <td><?= $row['orders_count'] ?></td>
                            <td><?= $row['checkbox_uses'] ?></td>
                            <td><span class="badge bg-success">Yes</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
