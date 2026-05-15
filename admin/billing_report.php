<?php
/**
 * resupply - Super Admin Billing Report (Robust Version)
 * Full visibility into vendor billing per README rules
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_super_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Billing Report';

require_once '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM vendors ORDER BY name");
$vendors = $stmt->fetchAll();
?>

<h1 class="mb-4">Monthly Billing Report – <?= date('F Y') ?></h1>

<p class="lead">Each vendor gets 2 free organizations. Additional active organizations = flat monthly fee.</p>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Total Orgs</th>
            <th>Billable This Month</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vendors as $vendor): 
            $billable = getBillableOrganizationsCount($vendor['id']);
        ?>
        <tr>
            <td><?= htmlspecialchars($vendor['name']) ?></td>
            <td><?= $vendor['org_count'] ?? '—' ?></td>
            <td><strong class="text-warning"><?= $billable ?></strong></td>
            <td>
                <?php if ($billable > 0): ?>
                    <span class="badge bg-warning">Invoice Due</span>
                <?php else: ?>
                    <span class="badge bg-success">Within Free Limit</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>