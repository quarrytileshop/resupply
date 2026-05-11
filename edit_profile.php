<?php
// edit_profile.php – Modified 2026-05-08 – Lines: 180
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');

    if (!empty($first_name) && !empty($last_name)) {
        $stmt = $pdo->prepare("UPDATE users SET first_name = :fn, last_name = :ln, phone_number = :phone WHERE id = :id");
        $stmt->execute([
            'fn'    => $first_name,
            'ln'    => $last_name,
            'phone' => $phone,
            'id'    => $user_id
        ]);
        $message = "✅ Profile updated successfully.";
        
        // Update session
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name']  = $last_name;
    } else {
        $error = "First and Last name are required.";
    }
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Edit Profile</h2>

                        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email (cannot be changed)</label>
                                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
                            <a href="reset_password.php" class="btn btn-outline-primary">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
