<?php
/**
 * resupply - Invite User Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Invite User - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name      = trim($_POST['first_name'] ?? '');
    $last_name       = trim($_POST['last_name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $organization_id = (int)($_POST['organization_id'] ?? 0);

    if ($first_name && $last_name && $email) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+48 hours'));

        $stmt = $pdo->prepare("INSERT INTO users 
            (first_name, last_name, email, username, organization_id, approval_status, reset_token, reset_expires, created_at) 
            VALUES (:first_name, :last_name, :email, :username, :org_id, 'pending', :token, :expires, NOW())");
        
        $username = strtolower($first_name . '.' . $last_name);
        
        $success = $stmt->execute([
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'username'   => $username,
            'org_id'     => $organization_id ?: null,
            'token'      => $token,
            'expires'    => $expires
        ]);

        if ($success) {
            $reset_link = "https://" . $_SERVER['HTTP_HOST'] . "/resupply/set_password.php?token=" . $token;
            $_SESSION['message'] = "User invited successfully!<br>They can set their password here:<br><strong>" . htmlspecialchars($reset_link) . "</strong>";
            header("Location: invite_user.php");
            exit;
        } else {
            $error = "Failed to invite user. Please try again.";
        }
    } else {
        $error = "First name, last name, and email are required.";
    }
}

// Fetch organizations for dropdown
$stmt = $pdo->prepare("SELECT id, name FROM organizations ORDER BY name");
$stmt->execute();
$organizations = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Invite New User</h1>
    <p class="text-muted">Send an invitation so a new user can register and set their password.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
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
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Assign to Organization (optional)</label>
                    <select name="organization_id" class="form-select">
                        <option value="">— No Organization Yet —</option>
                        <?php foreach ($organizations as $org): ?>
                        <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Send Invitation</button>
                    <a href="admin_users.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>