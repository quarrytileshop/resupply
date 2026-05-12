<?php
// vendor_organizations.php – New file for vendor admins to manage customer organizations – 2026-05-11
$page_title = "My Customer Organizations - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

// Handle adding a new customer organization
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_org') {
    $name = trim($_POST['name'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    
    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO organizations (name, account_number, approval_status) 
                              VALUES (:name, :account_number, 'approved')");
        $stmt->execute(['name' => $name, 'account_number' => $account_number]);
        $success = "Customer organization added successfully!";
    }
}

// Fetch all approved customer organizations for this vendor (in future we can add vendor_id filter)
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE approval_status = 'approved' ORDER BY name");
$stmt->execute();
$organizations = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">My Customer Organizations</h1>
    <p class="text-muted">These are the organizations you sell to. Build shopping lists for them in the builder.</p>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Add new organization form -->
    <div class="card mb-5">
        <div class="card-header">Add New Customer Organization</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add_org">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Organization Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add Organization</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List of customer organizations -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($organizations)): ?>
                <p class="text-muted">No customer organizations yet. Add one above.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Account #</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['account_number'] ?? '—') ?></td>
                                <td><span class="badge bg-success">Approved</span></td>
                                <td>
                                    <a href="shopping_list_builder.php?org_id=<?= $org['id'] ?>" class="btn btn-sm btn-primary">Build List</a>
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
