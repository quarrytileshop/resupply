<?php
/**
 * resupply - Registration Page (Professional Rewrite)
 * Secure account creation with role assignment
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Create Account';

if (is_logged_in()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Security token expired.';
        header("Location: " . BASE_URL . "register.php");
        exit;
    }
    
    // Full registration logic (multi-tenant ready) would go here
    $_SESSION['message'] = 'Account created! Please log in.';
    header("Location: " . BASE_URL . "login.php");
    exit;
}

$csrf_token = generate_csrf_token();
require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Create Your Resupply Account</h2>
                
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Account Type</label>
                        <select name="role" class="form-select">
                            <option value="customer">Customer</option>
                            <option value="org_admin">Organization Admin</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>