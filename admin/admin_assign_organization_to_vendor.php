<?php
// admin_assign_organization_to_vendor.php – Super Admin tool to assign organizations to vendors – 2026-05-11
$page_title = "Assign Organization to Vendor - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: dashboard.php");
    exit;
}

$success = '';
$error = '';

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $org_id    = (int)$_POST['org_id'];
    $vendor_id = (int)$_POST['vendor_id'];

    if ($org_id && $vendor_id) {
        $stmt = $pdo->prepare("UPDATE organizations SET vendor_id = :vendor_id WHERE id = :org_id");
        $stmt->execute(['vendor_id' => $vendor_id, 'org_id' => $org_id]);
        $success = "Organization successfully assigned to the vendor!";
    } else {
        $error = "Please select both an organization and a vendor.";
    }
}

// Fetch all organizations
$stmt = $pdo->query("SELECT id, name, vendor_id FROM organizations ORDER BY name");
$organizations = $stmt->fetchAll();

// Fetch all Vendor Admins (users with is_organization_admin = 1)
$stmt = $pdo->query("SELECT id, first_name, last_name, email FROM users WHERE is_organization_admin = 1 ORDER BY first_name");
$vendors = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Assign Organization to Vendor</h1>
    <p class="text-muted">Link existing organizations (like Holstein Testing) to a specific vendor.</p>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Organization</label>
                        <select name="org_id" class="form-select" required>
                            <option value="">— Select Organization —</option>
                            <?php foreach ($organizations as $org): ?>
                                <option value="<?= $org['id'] ?>" <?= $org['vendor_id'] ? 'disabled' : '' ?>>
                                    <?= htmlspecialchars($org['name']) ?>
                                    <?= $org['vendor_id'] ? ' (already assigned)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Assign to Vendor</label>
                        <select name="vendor_id" class="form-select" required>
                            <option value="">— Select Vendor —</option>
                            <?php foreach ($vendors as $v): ?>
                                <option value="<?= $v['id'] ?>">
                                    <?= htmlspecialchars($v['first_name'] . ' ' . $v['last_name']) ?> 
                                    (<?= htmlspecialchars($v['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" name="assign" class="btn btn-accent send-it-btn w-100 mt-4">
                    <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                    ASSIGN ORGANIZATION TO VENDOR
                </button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Super Admin Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
