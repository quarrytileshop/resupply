<?php
/**
 * resupply - Reset Password Page
 * Updated for new folder structure (May 14, 2026)
 * All includes and asset paths updated
 */

$page_title = "Reset Password - Resupply Rocket";
require_once 'includes/header.php';

$error = '';
$success = '';

$token = $_GET['token'] ?? '';
if (empty($token)) {
    $error = "Invalid or missing reset token.";
} else {
    // Check if token is valid and not expired
    $stmt = $pdo->prepare("SELECT id, first_name FROM users 
                           WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "This password reset link is invalid or has expired.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($password) || $password !== $confirm_password) {
            $error = "Passwords do not match or are empty.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET 
                password_hash = :password_hash,
                reset_token = NULL,
                reset_expires = NULL
                WHERE id = :id");
            
            $updated = $stmt->execute([
                'password_hash' => $password_hash,
                'id'            => $user['id']
            ]);

            if ($updated) {
                $success = "Your password has been successfully reset. You can now login.";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
        }
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <img src="assets/icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:120px;">
                    <h2 class="text-center mb-4">Reset Your Password</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <div class="text-center mt-4">
                            <a href="forgot_password.php" class="btn btn-primary">Request New Reset Link</a>
                        </div>
                    <?php elseif ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mt-4">
                            <a href="login.php" class="btn btn-success btn-lg">Go to Login</a>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted mb-4">Enter your new password below.</p>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="login.php">Back to Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>