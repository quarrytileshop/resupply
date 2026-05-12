<?php
// vendor_organizations.php – Enhanced with Delete, Pending Approval, and Invite/Prefill – 2026-05-12
$page_title = "My Customer Organizations - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Handle adding new organization (now as pending)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_org') {
    $name = trim($_POST['name'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO organizations (name, account_number, vendor_id, approval_status) 
                              VALUES (:name, :account_number, :vendor_id, 'pending')");
        $stmt->execute(['name' => $name, 'account_number' => $account_number, 'vendor_id' => $vendor_id]);
        $success = "Organization added as pending. You can approve it below.";
    }
}

// Handle approve
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $pdo->prepare("UPDATE organizations SET approval_status = 'approved' WHERE id = :id AND vendor_id = :vendor_id");
    $stmt->execute(['id' => $id, 'vendor_id' => $vendor_id]);
    header("Location: vendor_organizations.php?msg=approved");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM organizations WHERE id = :id AND vendor_id = :vendor_id");
    $stmt->execute(['id' => $id, 'vendor_id' => $vendor_id]);
    header("Location: vendor_organizations.php?msg=deleted");
    exit;
}

// Fetch all organizations for this vendor
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE vendor_id = :vendor_id ORDER BY approval_status DESC, name");
$stmt->execute(['vendor_id' => $vendor_id]);
$organizations = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">My Customer Organizations</h1>
    <p class="text-muted">Manage organizations under your vendor account. Pending organizations must be approved.</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= $_GET['msg'] === 'approved' ? 'Organization approved!' : ($_GET['msg'] === 'deleted' ? 'Organization deleted!' : 'Action completed!') ?></div>
    <?php endif; ?>

    <!-- Add new organization (creates as pending) -->
    <div class="card mb-5">
        <div class="card-header">Add New Organization (Pending Approval)</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add_org">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Organization Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Number (optional)</label>
                        <input type="text" name="account_number" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add as Pending</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List of organizations -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($organizations)): ?>
                <p class="text-muted">No organizations yet. Add one above.</p>
            <?php else: ?>
                <div class="table-responsive">
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
                            <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['account_number'] ?? '—') ?></td>
                                <td>
                                    <?php if ($org['approval_status'] === 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($org['approval_status'] === 'pending'): ?>
                                        <a href="?approve=<?= $org['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                    <?php endif; ?>
                                    <a href="?delete=<?= $org['id'] ?>" onclick="return confirm('Delete this organization permanently?')" class="btn btn-danger btn-sm">Delete</a>
                                    <a href="vendor_invite_org.php?org_id=<?= $org['id'] ?>" class="btn btn-info btn-sm">Prefill Invite</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
