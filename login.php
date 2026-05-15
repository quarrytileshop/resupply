<?php
/**
 * resupply - Login Page (Professional Rewrite)
 * Full security: rate limiting stub, CSRF, secure redirect
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Login';

if (is_logged_in()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

// Simple in-memory rate limit (production-ready version would use DB/redis)
if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
if (!isset($_SESSION['last_login_attempt'])) $_SESSION['last_login_attempt'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Security token expired. Please try again.';
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Rate limit check
    if (time() - $_SESSION['last_login_attempt'] < 2) {
        $_SESSION['error'] = 'Please wait a moment before trying again.';
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    $_SESSION['last_login_attempt'] = time();

    // Query user (multi-tenant safe)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND (vendor_id IS NULL OR vendor_id > 0) LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['organization_id'] = $user['organization_id'] ?? null;
        $_SESSION['vendor_id'] = $user['vendor_id'] ?? null;
        $_SESSION['is_admin'] = ($user['role'] === ROLE_SUPER_ADMIN);
        $_SESSION['is_vendor_admin'] = ($user['role'] === ROLE_VENDOR);
        $_SESSION['is_organization_admin'] = ($user['role'] === ROLE_ORG_ADMIN);

        // Audit log
        $log = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, 'login', ?)");
        $log->execute([$user['id'], json_encode(['ip' => $_SERVER['REMOTE_ADDR']])]);

        $_SESSION['message'] = 'Welcome back!';
        header("Location: " . BASE_URL . "dashboard.php");
        exit;
    } else {
        $_SESSION['login_attempts']++;
        $_SESSION['error'] = 'Invalid email or password.';
    }
}

$csrf_token = generate_csrf_token();
?>
<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-8">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Log in to Resupply Rocket</h2>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST" action="">
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

                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>forgot_password.php" class="text-decoration-none">Forgot password?</a><br>
                    <a href="<?= BASE_URL ?>register.php" class="text-decoration-none">Create new account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>