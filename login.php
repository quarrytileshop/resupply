<?php
/**
 * resupply - Login Page
 * Updated for new folder structure (May 14, 2026)
 * Simplified clean layout with full-height centered card
 * No extra containers, no strange background formatting behind the dialog
 * Uses Bootstrap full-screen flex centering for a modern, minimal look
 */

$page_title = "Login - Resupply Rocket";
require_once 'includes/header.php';

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
                $_SESSION['is_vendor_admin'] = (int)($user['is_vendor_admin'] ?? 0);
                $_SESSION['is_organization_admin'] = $user['is_organization_admin'] ?? 0;
                $_SESSION['organization_id'] = $user['organization_id'];
                $_SESSION['vendor_id'] = $user['vendor_id'] ?? null;
                $_SESSION['is_propane'] = $user['is_propane'];

                // Clear, mutually-exclusive role priority
                if ($user['is_admin']) {
                    $redirect = 'admin/admin_dashboard.php';
                } elseif ($user['is_vendor_admin']) {
                    $redirect = 'vendor/vendor_dashboard.php';
                } elseif ($user['is_organization_admin']) {
                    $redirect = 'organization_admin.php';
                } else {
                    $redirect = 'dashboard.php';
                }
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

<!-- Clean full-screen centered layout - no strange background/formatting issues -->
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
        <div class="card-body p-5">
            <!-- Rocket logo -->
            <img src="/assets/icons/logo-192.png" alt="Resupply Rocket" class="mx-auto d-block mb-4" style="max-width: 180px;">
            
            <h2 class="text-center mb-4 fw-bold">Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
            </form>

            <div class="text-center mt-4">
                <a href="register.php" class="text-decoration-none">Register New Account</a> | 
                <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>