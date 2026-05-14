<?php
/**
 * resupply - Admin Impersonate User Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Impersonate User - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];

    $stmt = $pdo->prepare("SELECT id, first_name, username, role FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $target_user = $stmt->fetch();

    if ($target_user) {
        // Store original admin session so we can restore later
        if (!isset($_SESSION['impersonating'])) {
            $_SESSION['original_user_id']   = $_SESSION['user_id'];
            $_SESSION['original_username']  = $_SESSION['username'];
            $_SESSION['original_role']      = $_SESSION['role'] ?? 'super_admin';
        }

        // Impersonate the user
        $_SESSION['user_id']   = $target_user['id'];
        $_SESSION['username']  = $target_user['username'];
        $_SESSION['first_name'] = $target_user['first_name'];
        $_SESSION['impersonating'] = true;

        $_SESSION['message'] = "You are now impersonating " . htmlspecialchars($target_user['first_name']) . ". <a href='../logout.php?restore=true'>Click here to stop impersonating</a>";

        header("Location: ../dashboard.php");
        exit;
    } else {
        $error = "User not found.";
    }
}

// Show list of users to impersonate
$stmt = $pdo->prepare("SELECT id, first_name, last_name, username, email FROM users WHERE id != :current_user ORDER BY first_name");
$stmt->execute(['current_user' => $_SESSION['user_id']]);
$users = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Impersonate User</h1>
    <p class="text-muted">Temporarily log in as another user (useful for testing and support).</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if ($users): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <a href="?user_id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Impersonate</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No other users found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>