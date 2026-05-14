<?php
/**
 * resupply - Admin Edit Vendor Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Edit Vendor - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

$vendor_id = (int)($_GET['id'] ?? 0);
if ($vendor_id <= 0) {
    $_SESSION['error'] = "Invalid vendor ID.";
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch current vendor data
$stmt = $pdo->prepare("SELECT * FROM vendors WHERE id = :id");
$stmt->execute(['id' => $vendor_id]);
$vendor = $stmt->fetch();

if (!$vendor) {
    $_SESSION['error'] = "Vendor not found.";
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');

    if ($name && $email) {
        $stmt = $pdo->prepare("UPDATE vendors SET 
            name = :name,
            email = :email,
            company = :company,
            phone = :phone,
            updated_at = NOW()
            WHERE id = :id");
        
        $success = $stmt->execute([
            'name'    => $name,
            'email'   => $email,
            'company' => $company,
            'phone'   => $phone,
            'id'      => $vendor_id
        ]);

        if ($success) {
            $_SESSION['message'] = "Vendor updated successfully!";
            header("Location: admin_edit_vendor.php?id=" . $vendor_id);
            exit;
        } else {
            $error = "Failed to update vendor.";
        }
    } else {
        $error = "Vendor name and email are required.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Edit Vendor</h1>
    <p class="text-muted">Editing vendor: <strong><?= htmlspecialchars($vendor['name'] ?? '') ?></strong></p>

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
                    <label class="form-label">Vendor Name / Contact Person</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= htmlspecialchars($vendor['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($vendor['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company" class="form-control" 
                           value="<?= htmlspecialchars($vendor['company'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" 
                           value="<?= htmlspecialchars($vendor['phone'] ?? '') ?>">
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Save Changes</button>
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