<?php
/**
 * resupply - Admin Assign Organization to Vendor Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Assign Organization to Vendor - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendor_id       = (int)($_POST['vendor_id'] ?? 0);
    $organization_id = (int)($_POST['organization_id'] ?? 0);

    if ($vendor_id > 0 && $organization_id > 0) {
        $stmt = $pdo->prepare("UPDATE vendors SET organization_id = :org_id WHERE id = :vendor_id");
        $success = $stmt->execute([
            'org_id'    => $organization_id,
            'vendor_id' => $vendor_id
        ]);

        if ($success) {
            $_SESSION['message'] = "Organization assigned to vendor successfully!";
            header("Location: admin_assign_organization_to_vendor.php");
            exit;
        } else {
            $error = "Failed to assign organization.";
        }
    } else {
        $error = "Please select both a vendor and an organization.";
    }
}

// Fetch vendors and organizations for dropdowns
$vendors = $pdo->query("SELECT id, name, company FROM vendors ORDER BY name")->fetchAll();
$organizations = $pdo->query("SELECT id, name FROM organizations ORDER BY name")->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Assign Organization to Vendor</h1>
    <p class="text-muted">Link a vendor to a specific organization so they can see its shopping lists and orders.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Select Vendor</label>
                    <select name="vendor_id" class="form-select" required>
                        <option value="">— Choose Vendor —</option>
                        <?php foreach ($vendors as $v): ?>
                        <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['company'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Assign to Organization</label>
                    <select name="organization_id" class="form-select" required>
                        <option value="">— Choose Organization —</option>
                        <?php foreach ($organizations as $org): ?>
                        <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Assign Organization</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>