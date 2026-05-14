<?php
/**
 * resupply - Vendor Register Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Vendor Registration - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name'] ?? '');
    $company      = trim($_POST['company'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $password     = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Name, email, and password are required.";
    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check if vendor email already exists
        $stmt = $pdo->prepare("SELECT id FROM vendors WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error = "A vendor with this email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO vendors 
                (name, company, email, phone, password_hash, approved, created_at) 
                VALUES (:name, :company, :email, :phone, :hash, 0, NOW())");
            
            $success_insert = $stmt->execute([
                'name'    => $name,
                'company' => $company,
                'email'   => $email,
                'phone'   => $phone,
                'hash'    => $password_hash
            ]);

            if ($success_insert) {
                $success = "Vendor account created successfully! Your account is pending approval. You will receive an email once approved.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body p-5">
                    <img src="../assets/icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:120px;">
                    <h2 class="text-center mb-4">Vendor Registration</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mt-4">
                            <a href="../login.php" class="btn btn-primary">Return to Login</a>
                        </div>
                    <?php else: ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Your Name / Contact Person</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number (optional)</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Register as Vendor</button>
                        </form>

                        <div class="text-center mt-4">
                            Already have a vendor account? <a href="../login.php">Login here</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>