<?php
// forgot_password.php – Updated 2026-05-11 to use header + footer + professional styles
$page_title = "Forgot Password - Resupply Rocket";
require_once 'header.php';

// Your original forgot-password logic (email sending, token, etc.) stays here
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="mb-4 text-center">Reset Password</h1>
                    <p class="text-muted text-center">Enter your email and we’ll send you a reset link.</p>

                    <!-- Your original form stays exactly the same -->
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
