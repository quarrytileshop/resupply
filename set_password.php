<?php
// set_password.php - Modification Date: August 27, 2025, 10:00 AM - Total Lines: 100
require_once 'config.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing set_password.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
$message = '';
$error = '';
$token = $_GET['token'] ?? '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $token) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT pr.id, pr.user_id, pr.expires_at, pr.used, u.approval_status FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE token = :token");
            $stmt->execute(['token' => $token]);
            $reset = $stmt->fetch();
            if (!$reset || $reset['used'] || strtotime($reset['expires_at']) < time()) {
                $error = "Invalid or expired token.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $approval_status = $reset['approval_status']; // Keep existing, no auto-approve for resets
                $stmt = $pdo->prepare("UPDATE users SET password_hash = :password_hash, approval_status = :approval_status WHERE id = :user_id");
                $stmt->execute(['password_hash' => $password_hash, 'approval_status' => $approval_status, 'user_id' => $reset['user_id']]);
                $stmt = $pdo->prepare("UPDATE password_resets SET used = 1 WHERE id = :id");
                $stmt->execute(['id' => $reset['id']]);
                $message = "Password set successfully. You can now login.";
            }
        } catch (PDOException $e) {
            error_log("Set password error: " . $e->getMessage());
            $error = "Error setting password. Try again.";
        }
    }
}
if (!$token) {
    $error = "No token provided.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Set Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Set Password</h1>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if (!$error || $_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <form method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label>Password:</label><input type="password" name="password" required class="form-control" id="password"><br>
                <label>Confirm Password:</label><input type="password" name="confirm_password" required class="form-control" id="confirm_password"><br>
                <button type="submit" class="btn btn-primary w-100">Set Password</button>
            </form>
        <?php endif; ?>
        <a href="login.php">Login</a>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                alert('Passwords do not match.');
                e.preventDefault();
            } else if (document.getElementById('password').value.length < 8) {
                alert('Password must be at least 8 characters.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
