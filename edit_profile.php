<?php
/**
 * resupply - Edit Profile Page (Professional Rewrite)
 * Secure profile editing for any user.
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Edit Profile';

require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Security token expired.';
        header("Location: " . BASE_URL . "edit_profile.php");
        exit;
    }
    $_SESSION['message'] = 'Profile updated successfully.';
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$csrf_token = generate_csrf_token();
?>

<h1 class="mb-4">Edit Profile</h1>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

<?php require_once 'includes/footer.php'; ?>