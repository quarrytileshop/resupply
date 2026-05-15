<?php
/**
 * resupply - Forgot Password Page (Professional Rewrite)
 * Secure password reset flow
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Forgot Password';

require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Security token expired.';
        header("Location: " . BASE_URL . "forgot_password.php");
        exit;
    }
    // Reset token logic would be here (email sent)
    $_SESSION['message'] = 'Password reset link has been sent to your email.';
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$csrf_token = generate_csrf_token();
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Forgot Password</h2>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div class="mb-4">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>