<?php
// login.php – Modified 2025-03-11 10:15 PDT – Lines: 85
require_once 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $password = $_POST['password'] ?? '';

    echo "<pre>Debug: POST received. Email: '$email', Password provided: " . (strlen($password) > 0 ? 'Yes' : 'No') . "</pre>";

    if ($email && $password) {
        try {
            echo "<pre>Debug: Preparing query...</pre>";
            $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, password_hash, is_admin, approval_status, suspended, organization_id, is_propane, is_organization_admin FROM users WHERE email = :email");
            echo "<pre>Debug: Query prepared. Executing with email '$email'...</pre>";
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            echo "<pre>Debug: Query executed. Row found? " . ($user ? 'Yes' : 'No') . "</pre>";

            if ($user) {
                echo "<pre>Debug: User row found. ID: {$user['id']}, Approval: {$user['approval_status']}, Suspended: {$user['suspended']}</pre>";

                if ($user['approval_status'] !== 'approved') {
                    $error = "Account not approved.";
                } elseif ($user['suspended']) {
                    $error = "Account suspended.";
                } elseif (password_verify($password, $user['password_hash'])) {
                    echo "<pre>Debug: Password verified successfully.</pre>";
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    $_SESSION['is_organization_admin'] = $user['is_organization_admin'];

                    $redirect = $user['is_admin'] ? 'admin_dashboard.php' : ($user['is_propane'] ? 'order.php' : 'dashboard.php');
                    header("Location: $redirect");
                    exit;
                } else {
                    $error = "Invalid email or password.";
                    echo "<pre>Debug: Password verify FAILED. Stored hash: {$user['password_hash']}</pre>";
                }
            } else {
                $error = "Invalid email or password.";
                echo "<pre>Debug: No user found for email '$email'.</pre>";
            }
        } catch (PDOException $e) {
            error_log("Login query error: " . $e->getMessage());
            $error = "Database error during login: " . htmlspecialchars($e->getMessage());
            echo "<pre>Debug: PDO Exception: " . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    } else {
        $error = "Please enter email and password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Email:</label><input type="email" name="email" required class="form-control"><br>
            <label>Password:</label><input type="password" name="password" required class="form-control"><br>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <a href="register.php">Register</a> | <a href="forgot_password.php">Forgot Password?</a>
    </div>
</body>
</html>
