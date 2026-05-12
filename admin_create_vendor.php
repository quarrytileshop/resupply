<?php
// admin_create_vendor.php – Super Admin page to create new Vendor Admins – 2026-05-11
$page_title = "Create New Vendor Admin - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $company_name   = trim($_POST['company_name'] ?? ''); // optional vendor company name

    if ($first_name && $last_name && $email && $password) {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users 
                (first_name, last_name, email, password_hash, is_organization_admin, approval_status, vendor_id)
                VALUES (:first_name, :last_name, :email, :password_hash, 1, 'approved', LAST_INSERT_ID())");
            $stmt->execute([
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'email'         => $email,
                'password_hash' => $password_hash
            ]);

            $new_user_id = $pdo->lastInsertId();

            // Set vendor_id = the new user’s own ID (self-isolation)
            $stmt = $pdo->prepare("UPDATE users SET vendor_id = :vendor_id WHERE id = :id");
            $stmt->execute(['vendor_id' => $new_user_id, 'id' => $new_user_id]);

            // Optional: create a default organization for this vendor
            if ($company_name) {
                $stmt = $pdo->prepare("INSERT INTO organizations (name, vendor_id, approval_status) 
                                      VALUES (:name, :vendor_id, 'approved')");
                $stmt->execute(['name' => $company_name, 'vendor_id' => $new_user_id]);
            }

            $success = "Vendor Admin <strong>" . htmlspecialchars($email) . "</strong> created successfully!<br>Password: <code>" . htmlspecialchars($password) . "</code>";
        }
    } else {
        $error = "All required fields must be filled.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-3">Create New Vendor Admin</h1>
    <p class="text-muted">This will create a full Vendor Admin account with isolated access.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password (will be shown once)</label>
                        <input type="text" name="password" class="form-control" value="password123" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Vendor Company Name (optional)</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Texas Tile Supply">
                    </div>
                </div>

                <button type="submit" class="btn btn-accent send-it-btn w-100 mt-4">
                    <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                    CREATE VENDOR ADMIN
                </button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Super Admin Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
