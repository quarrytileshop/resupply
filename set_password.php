<?php
/**
 * resupply - Set Password Page
 * Updated for new folder structure (May 14, 2026)
 * All includes and asset paths updated
 */

$page_title = "Set Password - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

$error = '';
$success = '';

$token = $_GET['token'] ?? '';
if (empty($token)) {
    $error = "Invalid or missing token.";
} else {
    // Verify token (simple version - matches the token flow from registration/forgot-password)
    $stmt = $pdo->prepare("SELECT id, first_name FROM users 
                           WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "This link is invalid or has expired.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($password) || $password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET 
                password_hash = :password_hash,
                reset_token = NULL,
                reset_expires = NULL,
                approval_status = 'approved'
                WHERE id = :id");
            
            $updated = $stmt->execute([
                'password_hash' => $password_hash,
                'id'            => $user['id']
            ]);

            if ($updated) {
                $success = "Password set successfully! You can now log in.";
                // Auto-login the user
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
            } else {
                $error = "Failed to set password. Please try again.";
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
                    <h2 class="text-center mb-4">Set Your Password</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php elseif ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mt-4">
                            <a href="login.php" class="btn btn-success btn-lg">Go to Login</a>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted mb-4">Choose a secure password for your account.</p>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Set Password</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>