<?php
// forgot_password.php – Full rewrite with original logic – Updated 2026-05-11
$page_title = "Forgot Password - Resupply Rocket";
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if ($email) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate reset token (simple version – you can expand with expiry)
            $token = bin2hex(random_bytes(32));
            
            $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id");
            $stmt->execute(['token' => $token, 'id' => $user['id']]);
            
            // Send email using your existing email_functions.php
            require_once 'email_functions.php';
            sendPasswordResetEmail($email, $token);
            
            $success = "Password reset link has been sent to your email.";
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
                    <h1 class="mb-4 text-center">Reset Password</h1>
                    <p class="text-muted text-center">Enter your email and we’ll send you a reset link.</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="login.php">← Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
