<?php
/**
 * resupply - Vendor Organizations Page (Robust Billing Version)
 * Shows clear Free / Billable / No Activity status per README
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_vendor()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Your Organizations';

require_once '../includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM organizations WHERE vendor_id = ? ORDER BY name");
$stmt->execute([$_SESSION['vendor_id'] ?? 0]);
$orgs = $stmt->fetchAll();
?>

<h1 class="mb-4">Your Organizations</h1>

<div class="alert alert-info">
    You have <strong>2 free organization slots</strong>. Any additional organization that has activity this month is billable (flat fee).
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Organization</th>
                <th>Status This Month</th>
                <th>Users</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orgs as $index => $org): 
                $active = hasActivityThisMonth($org['id']);
                $isBillable = $active && ($index + 1 > 2);
            ?>
            <tr>
                <td><?= htmlspecialchars($org['name']) ?></td>
                <td>
                    <?php if ($active && $isBillable): ?>
                        <span class="badge bg-warning">Billable This Month</span>
                    <?php elseif ($active): ?>
                        <span class="badge bg-success">Active (Free Slot)</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">No Activity</span>
                    <?php endif; ?>
                </td>
                <td><?= $org['user_count'] ?? '—' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>