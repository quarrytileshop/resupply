<?php
/**
 * resupply - Admin Create Vendor Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Create New Vendor - Resupply Rocket";
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
    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');

    if ($name && $email) {
        $stmt = $pdo->prepare("INSERT INTO vendors 
            (name, email, company, phone, created_at, approved) 
            VALUES (:name, :email, :company, :phone, NOW(), 0)");
        
        $success = $stmt->execute([
            'name'    => $name,
            'email'   => $email,
            'company' => $company,
            'phone'   => $phone
        ]);

        if ($success) {
            $_SESSION['message'] = "Vendor '" . htmlspecialchars($name) . "' created successfully! They can now register.";
            header("Location: admin_create_vendor.php");
            exit;
        } else {
            $error = "Failed to create vendor. Please try again.";
        }
    } else {
        $error = "Vendor name and email are required.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Create New Vendor</h1>
    <p class="text-muted">Add a new vendor to the system. They will receive an invitation to register.</p>

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
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number (optional)</label>
                    <input type="tel" name="phone" class="form-control">
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Create Vendor</button>
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