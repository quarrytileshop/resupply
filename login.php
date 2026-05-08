<?php
// login.php – Modified 2026-05-08
require_once 'config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, password_hash, is_admin, approval_status, suspended, organization_id, is_propane 
                               FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['approval_status'] !== 'approved') {
                $error = "Account is pending approval.";
            } elseif ($user['suspended']) {
                $error = "Account is suspended.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['organization_id'] = $user['organization_id'];
                $_SESSION['is_propane'] = $user['is_propane'];

                $redirect = $user['is_admin'] ? 'admin_dashboard.php' : ($user['is_propane'] ? 'order.php' : 'dashboard.php');
                header("Location: $redirect");
                exit;
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <img src="icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:150px;">
                        <h2 class="text-center mb-4">Login</h2>
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="register.php">Register</a> | <a href="forgot_password.php">Forgot Password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
