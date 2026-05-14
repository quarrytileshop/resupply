<?php
/**
 * resupply - Register Page
 * Updated for new folder structure (May 14, 2026)
 * All includes and asset paths updated
 */

$page_title = "Register - Resupply Rocket";
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $username       = trim($_POST['username'] ?? '');
    $password       = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $organization_id = (int)($_POST['organization_id'] ?? 0);

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check if email or username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        if ($stmt->rowCount() > 0) {
            $error = "Email or username already exists.";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users 
                (first_name, last_name, email, username, password_hash, organization_id, approval_status, created_at) 
                VALUES 
                (:first_name, :last_name, :email, :username, :password_hash, :organization_id, 'pending', NOW())");
            
            $success_insert = $stmt->execute([
                'first_name'     => $first_name,
                'last_name'      => $last_name,
                'email'          => $email,
                'username'       => $username,
                'password_hash'  => $password_hash,
                'organization_id' => $organization_id ?: null
            ]);

            if ($success_insert) {
                $success = "Registration successful! Your account is pending approval. You will receive an email when approved.";
                // Optionally send notification email to admin here
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
                    <img src="assets/icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:120px;">
                    <h2 class="text-center mb-4">Create New Account</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mt-4">
                            <a href="login.php" class="btn btn-primary">Go to Login</a>
                        </div>
                    <?php else: ?>
                        <form method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
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

                            <!-- Optional: Organization selection (if pre-register is enabled) -->
                            <div class="mb-3">
                                <label class="form-label">Organization (optional)</label>
                                <select name="organization_id" class="form-select">
                                    <option value="">— None / New Organization —</option>
                                    <!-- Populated by admin pre-register if needed -->
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Register Account</button>
                        </form>

                        <div class="text-center mt-4">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>