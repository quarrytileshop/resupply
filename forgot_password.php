<?php
/**
 * resupply - Forgot Password Page
 * Updated for new folder structure (May 14, 2026)
 * All includes and asset paths updated
 */

$page_title = "Forgot Password - Resupply Rocket";
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email) {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id, first_name FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate reset token (simple version - in production use better token)
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id");
            $stmt->execute([
                'token'    => $token,
                'expires'  => $expires,
                'id'       => $user['id']
            ]);

            // Send reset email (using email_functions.php)
            $reset_link = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;
            $subject = "Password Reset Request - Resupply Rocket";
            $body = "<p>Hi " . htmlspecialchars($user['first_name']) . ",</p>";
            $body .= "<p>You requested a password reset. Click the link below:</p>";
            $body .= "<p><a href='" . $reset_link . "'>Reset My Password</a></p>";
            $body .= "<p>This link expires in 1 hour.</p>";

            if (send_email($email, $subject, $body)) {
                $success = "Password reset link has been sent to your email.";
            } else {
                $error = "Failed to send email. Please try again later.";
            }
        } else {
            $error = "No account found with that email.";
        }
    } else {
        $error = "Please enter your email address.";
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <img src="assets/icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:120px;">
                    <h2 class="text-center mb-4">Forgot Password</h2>
                    <p class="text-center text-muted mb-4">Enter your email and we'll send you a reset link.</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mt-4">
                            <a href="login.php" class="btn btn-primary">Back to Login</a>
                        </div>
                    <?php else: ?>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
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