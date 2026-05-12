<?php
// register.php – Full expanded version with pre-filled vendor invite support – 2026-05-12
$page_title = "Register - Resupply Rocket";
require_once 'header.php';

$error = '';
$success = '';

// Pre-filled values from vendor invite link
$pre_vendor_id = (int)($_GET['vendor'] ?? 0);
$pre_org_id    = (int)($_GET['org'] ?? 0);
$pre_org_name  = '';

if ($pre_org_id) {
    $stmt = $pdo->prepare("SELECT name FROM organizations WHERE id = :id");
    $stmt->execute(['id' => $pre_org_id]);
    $row = $stmt->fetch();
    if ($row) $pre_org_name = $row['name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $organization_id = $pre_org_id ?: (int)($_POST['organization_id'] ?? 0);

    if ($first_name && $last_name && $email && $password && $organization_id) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users 
                (first_name, last_name, email, password_hash, organization_id, approval_status, is_admin, is_propane, vendor_id) 
                VALUES (:first_name, :last_name, :email, :password_hash, :organization_id, 'pending', 0, 0, :vendor_id)");
            $stmt->execute([
                'first_name'     => $first_name,
                'last_name'      => $last_name,
                'email'          => $email,
                'password_hash'  => $password_hash,
                'organization_id'=> $organization_id,
                'vendor_id'      => $pre_vendor_id
            ]);

            $success = "Account created successfully! Awaiting admin approval.";
            
            // Optional: send notification to superadmin
            require_once 'email_functions.php';
            sendApprovalNotification($email, $first_name . ' ' . $last_name);
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch organizations for dropdown (only if not pre-filled)
$organizations = [];
if (!$pre_org_id) {
    $stmt = $pdo->query("SELECT id, name FROM organizations ORDER BY name");
    $organizations = $stmt->fetchAll();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="mb-4 text-center">Create New Account</h1>
                    <p class="text-muted text-center mb-4">Join your organization on Resupply Rocket</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <!-- Organization field -->
                            <?php if ($pre_org_id && $pre_org_name): ?>
                                <div class="col-12">
                                    <label class="form-label">Organization</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($pre_org_name) ?>" readonly>
                                    <input type="hidden" name="organization_id" value="<?= $pre_org_id ?>">
                                </div>
                            <?php else: ?>
                                <div class="col-12">
                                    <label class="form-label">Organization</label>
                                    <select name="organization_id" class="form-select" required>
                                        <option value="">Select your organization</option>
                                        <?php foreach ($organizations as $org): ?>
                                            <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-4 py-3">Register Account</button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="login.php">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
