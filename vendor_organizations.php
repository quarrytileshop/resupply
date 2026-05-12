<?php
// vendor_organizations.php – Professional central hub with Pending/Approved tabs – 2026-05-12
$page_title = "My Customers - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;
$tab = $_GET['tab'] ?? 'approved';

// Handle actions
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $pdo->prepare("UPDATE organizations SET approval_status = 'approved' WHERE id = :id AND vendor_id = :vendor_id")
        ->execute(['id' => $id, 'vendor_id' => $vendor_id]);
    header("Location: vendor_organizations.php?tab=pending&msg=approved");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM organizations WHERE id = :id AND vendor_id = :vendor_id")
        ->execute(['id' => $id, 'vendor_id' => $vendor_id]);
    header("Location: vendor_organizations.php?msg=deleted");
    exit;
}

// Fetch organizations
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE vendor_id = :vendor_id ORDER BY name");
$stmt->execute(['vendor_id' => $vendor_id]);
$all_orgs = $stmt->fetchAll();

$pending_orgs = array_filter($all_orgs, fn($o) => $o['approval_status'] === 'pending');
$approved_orgs = array_filter($all_orgs, fn($o) => $o['approval_status'] === 'approved');
?>
<div class="container mt-4">
    <h1 class="mb-3">My Customers</h1>
    <p class="text-muted">Manage the organizations you serve. Pending organizations must be approved before they can use the system.</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= $_GET['msg'] === 'approved' ? 'Organization approved successfully!' : 'Organization deleted.' ?></div>
    <?php endif; ?>

    <!-- Add new organization -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="vendor_organizations.php">
                <input type="hidden" name="action" value="add_org">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Organization Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Holstein Testing" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Number (optional)</label>
                        <input type="text" name="account_number" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">+ Add Organization</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'pending' ? 'active' : '' ?>" href="?tab=pending">Pending (<?= count($pending_orgs) ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $tab === 'approved' ? 'active' : '' ?>" href="?tab=approved">Approved (<?= count($approved_orgs) ?>)</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">
            <?php
            $display_orgs = ($tab === 'pending') ? $pending_orgs : $approved_orgs;
            if (empty($display_orgs)): ?>
                <p class="text-muted">No <?= $tab ?> organizations yet.</p>
            <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Account #</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($display_orgs as $org): ?>
                        <tr>
                            <td><?= htmlspecialchars($org['name']) ?></td>
                            <td><?= htmlspecialchars($org['account_number'] ?? '—') ?></td>
                            <td>
                                <span class="badge <?= $org['approval_status'] === 'approved' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= ucfirst($org['approval_status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($org['approval_status'] === 'pending'): ?>
                                    <a href="?approve=<?= $org['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                <?php endif; ?>
                                <a href="vendor_invite_org.php?org_id=<?= $org['id'] ?>" class="btn btn-info btn-sm">Invite / Prefill</a>
                                <a href="?delete=<?= $org['id'] ?>" onclick="return confirm('Delete this organization permanently?')" class="btn btn-danger btn-sm">Delete</a>
                            </td>
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
