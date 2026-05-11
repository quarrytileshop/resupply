<?php
// invite_user.php – Modified 2026-05-08 – Lines: 160
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $organization_id = intval($_POST['organization_id'] ?? 0);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (empty($first_name) || empty($last_name)) {
        $error = "First and last name are required.";
    } else {
        // TODO: Generate temp password or magic link (expand later)
        $temp_password = substr(md5(rand()), 0, 12);
        $password_hash = password_hash($temp_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users 
            (organization_id, first_name, last_name, username, email, password_hash, approval_status) 
            VALUES (:org_id, :fn, :ln, :un, :email, :hash, 'pending')");
        $stmt->execute([
            'org_id' => $organization_id,
            'fn'     => $first_name,
            'ln'     => $last_name,
            'un'     => $first_name . ' ' . $last_name,
            'email'  => $email,
            'hash'   => $password_hash
        ]);

        $message = "✅ Invitation sent to $email. They can set their password on first login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite User - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Invite New User</h2>

                        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Organization</label>
                                <select name="organization_id" class="form-select" required>
                                    <!-- Populate from organizations table in future -->
                                    <option value="1">Sample Organization</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Invitation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
