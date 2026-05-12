<?php
// login.php – Full expanded version with vendor_id session for isolation – 2026-05-11
$page_title = "Login - Resupply Rocket";
require_once 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT u.*, o.vendor_id 
                               FROM users u 
                               LEFT JOIN organizations o ON u.organization_id = o.id 
                               WHERE u.email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['approval_status'] !== 'approved') {
                $error = "Your account is pending approval.";
            } elseif ($user['suspended']) {
                $error = "Your account has been suspended.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['is_organization_admin'] = $user['is_organization_admin'] ?? 0;
                $_SESSION['organization_id'] = $user['organization_id'];
                $_SESSION['vendor_id'] = $user['vendor_id'] ?? null;
                $_SESSION['is_propane'] = $user['is_propane'];

                $redirect = $user['is_admin'] ? 'admin_dashboard.php' : 
                           ($user['is_organization_admin'] ? 'vendor_dashboard.php' : 'dashboard.php');
                header("Location: $redirect");
                exit;
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please enter email and password.";
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <img src="icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:150px;">
                    <h2 class="text-center mb-4">Login</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="register.php">Register New Account</a> | 
                        <a href="forgot_password.php">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
