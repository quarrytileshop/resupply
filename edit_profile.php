<?php
/**
 * resupply - Edit Profile Page
 * Updated for new folder structure (May 14, 2026)
 * All includes, asset paths, and internal links updated
 */

$page_title = "Edit Profile - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = "First name, last name, and email are required.";
    } else {
        // Verify current password if changing password
        $password_change = false;
        if (!empty($new_password)) {
            if (empty($current_password)) {
                $error = "Current password is required to set a new password.";
            } else {
                $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $user = $stmt->fetch();

                if ($user && password_verify($current_password, $user['password_hash'])) {
                    $password_change = true;
                } else {
                    $error = "Current password is incorrect.";
                }
            }
        }

        if (empty($error)) {
            // Update profile
            $stmt = $pdo->prepare("UPDATE users SET 
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email
                WHERE id = :id");
            
            $success = $stmt->execute([
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'email'      => $email,
                'id'         => $_SESSION['user_id']
            ]);

            // Update password if requested
            if ($success && $password_change) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
                $stmt->execute(['hash' => $new_hash, 'id' => $_SESSION['user_id']]);
            }

            if ($success) {
                // Update session
                $_SESSION['first_name'] = $first_name;
                $_SESSION['message'] = "Profile updated successfully!";
                header("Location: edit_profile.php");
                exit;
            } else {
                $error = "Failed to update profile. Please try again.";
            }
        }
    }
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<div class="container mt-4">
    <h1 class="mb-4">Edit Profile</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" 
                               value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" 
                               value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>

                <hr>
                <h5 class="mb-3">Change Password (optional)</h5>
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" 
                           placeholder="Leave blank to keep current password">
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Save Changes</button>
                    <a href="dashboard.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>