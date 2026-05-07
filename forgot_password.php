<?php
// forgot_password.php - Modification Date: August 21, 2025, 3:00 PM - Total Lines: 100
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing forgot_password.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires)");
                $stmt->execute(['user_id' => $user['id'], 'token' => $token, 'expires' => $expires]);
                $link = "https://test.resupplyrocket.com/set_password.php?token=$token";
                $subject = "Reset Your Password for Resupply Rocket";
                $html_body = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>Password Reset</h2><p>Click the link to reset your password:</p><a href="' . $link . '">Reset Password</a><p>Link expires in 1 hour.</p></div></body></html>';
                $plain_body = "Reset your password: $link (expires in 1 hour).";
                if (send_email($email, $subject, $html_body, $plain_body)) {
                    $message = "Password reset link sent to your email.";
                } else {
                    $error = "Failed to send reset email.";
                }
            } else {
                $error = "Email not found.";
            }
        } catch (PDOException $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $error = "Database error.";
        }
    } else {
        $error = "Enter your email.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Forgot Password</h1>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Email:</label><input type="email" name="email" required class="form-control"><br>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
