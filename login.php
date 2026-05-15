<?php
/**
 * resupply - Login Page (Fixed Vendor Redirect)
 * Robust session setup for vendors
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Login';

if (is_logged_in()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Security token expired. Please try again.';
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // FORCE ROBUST SESSION FOR VENDORS
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['organization_id'] = $user['organization_id'] ?? null;
        $_SESSION['vendor_id'] = $user['vendor_id'] ?? null;
        $_SESSION['is_admin'] = ($user['role'] === ROLE_SUPER_ADMIN);
        $_SESSION['is_vendor_admin'] = ($user['role'] === ROLE_VENDOR || $user['role'] === 'vendor_admin');
        $_SESSION['is_organization_admin'] = ($user['role'] === ROLE_ORG_ADMIN);

        logUsage($user['id'], $user['organization_id'] ?? 0, $user['vendor_id'] ?? 0, 'login');

        $_SESSION['message'] = 'Welcome back!';
        
        // IMMEDIATE REDIRECT BASED ON ROLE
        if (is_vendor()) {
            header("Location: " . BASE_URL . "vendor/vendor_dashboard.php");
        } elseif (is_super_admin()) {
            header("Location: " . BASE_URL . "admin/admin_dashboard.php");
        } elseif (is_org_admin()) {
            header("Location: " . BASE_URL . "organization_admin.php");
        } else {
            header("Location: " . BASE_URL . "dashboard.php");
        }
        exit;
    } else {
        $_SESSION['error'] = 'Invalid email or password.';
    }
}

$csrf_token = generate_csrf_token();
require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Log in to Resupply Rocket</h2>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>