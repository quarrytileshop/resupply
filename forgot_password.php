<?php
// forgot_password.php – Modified 2026-05-08 – Lines: 120
require_once 'config.php';
session_start();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->fetch()) {
            // TODO: Generate reset token and send email (expand later)
            $message = "If an account exists with that email, a password reset link has been sent.";
        } else {
            $message = "If an account exists with that email, a password reset link has been sent."; // Security: don't reveal non-existence
        }
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <img src="icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:150px;">
                        <h2 class="text-center mb-4">Forgot Password</h2>

                        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
