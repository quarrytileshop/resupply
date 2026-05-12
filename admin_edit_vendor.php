<?php
// admin_edit_vendor.php – Edit existing vendor details – 2026-05-12
$page_title = "Edit Vendor - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin'] || !isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = (int)$_GET['id'];
$error = '';
$success = '';

// Load current vendor
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND is_organization_admin = 1");
$stmt->execute(['id' => $id]);
$vendor = $stmt->fetch();

if (!$vendor) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Vendor not found.</div></div>';
    require_once 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($first_name && $last_name && $email) {
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email";
        $params = ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'id' => $id];

        if (!empty($password)) {
            $sql .= ", password_hash = :password_hash";
            $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $success = "Vendor updated successfully!";
        // Refresh data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $vendor = $stmt->fetch();
    } else {
        $error = "Name and email are required.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-3">Edit Vendor</h1>
    <p class="text-muted">Editing: <?= htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']) ?></p>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($vendor['first_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($vendor['last_name']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($vendor['email']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">Save Changes</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
