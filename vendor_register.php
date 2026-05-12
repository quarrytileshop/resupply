<?php
// vendor_register.php – Public vendor self-signup with pending approval – 2026-05-11
$page_title = "Register as Vendor - Resupply Rocket";
require_once 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password     = $_POST['password'] ?? '';
    $company_name = trim($_POST['company_name'] ?? '');

    if ($first_name && $last_name && $email && $password && $company_name) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users 
                (first_name, last_name, email, password_hash, is_organization_admin, approval_status) 
                VALUES (:first_name, :last_name, :email, :password_hash, 1, 'pending')");
            $stmt->execute([
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'email'         => $email,
                'password_hash' => $password_hash
            ]);

            $new_vendor_id = $pdo->lastInsertId();

            // Create default organization for this vendor
            $stmt = $pdo->prepare("INSERT INTO organizations (name, vendor_id, approval_status) 
                                  VALUES (:name, :vendor_id, 'approved')");
            $stmt->execute(['name' => $company_name, 'vendor_id' => $new_vendor_id]);

            $success = "Your vendor account has been submitted for approval!<br>You will receive an email when approved.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <h1 class="mb-4 text-center">Register as a Vendor</h1>
                    <p class="text-muted text-center mb-4">Sign up to sell Resupply Rocket to your customers</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="e.g. Holstein Tile Supply" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Business Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-accent send-it-btn w-100 mt-4">
                            <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                            SUBMIT FOR APPROVAL
                        </button>
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
