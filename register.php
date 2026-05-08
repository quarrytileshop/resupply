<?php
// register.php – Modified 2026-05-08 – Final Version
require_once 'config.php';
require_once 'email_functions.php';
session_start();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_type = $_POST['registration_type'] ?? '';

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "All required fields must be filled.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            // Create unique username
            $base_username = $first_name . ' ' . $last_name;
            $username = $base_username;
            $counter = 1;
            while (true) {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :un");
                $stmt->execute(['un' => $username]);
                if (!$stmt->fetch()) break;
                $username = $base_username . ' ' . $counter++;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            if ($registration_type === 'new_company') {
                $name            = trim($_POST['name'] ?? '');
                $address         = trim($_POST['address'] ?? '');
                $mailing_address = trim($_POST['mailing_address'] ?? '');
                $contact_name    = trim($_POST['contact_name'] ?? '');
                $contact_email   = trim($_POST['contact_email'] ?? '');
                $type            = $_POST['organization_type'] ?? 'retail';
                $resale_number   = trim($_POST['organization_resale_number'] ?? '');

                if (empty($name) || empty($address) || empty($contact_name) || empty($contact_email)) {
                    $error = "All organization fields are required.";
                } elseif ($type === 'wholesale' && empty($resale_number)) {
                    $error = "Resale Number is required for Wholesale organizations.";
                } else {
                    try {
                        // Insert Organization
                        $stmt = $pdo->prepare("INSERT INTO organizations 
                            (name, address, mailing_address, contact_name, contact_email, type, resale_number, approval_status) 
                            VALUES (:name, :address, :mailing, :contact_name, :contact_email, :type, :resale, 'pending')");
                        $stmt->execute([
                            'name'          => $name,
                            'address'       => $address,
                            'mailing'       => $mailing_address,
                            'contact_name'  => $contact_name,
                            'contact_email' => $contact_email,
                            'type'          => $type,
                            'resale'        => $resale_number
                        ]);
                        $org_id = $pdo->lastInsertId();

                        // Insert User
                        $stmt = $pdo->prepare("INSERT INTO users 
                            (organization_id, first_name, last_name, username, email, phone_number, password_hash, approval_status) 
                            VALUES (:org_id, :fn, :ln, :un, :email, :phone, :hash, 'pending')");
                        $stmt->execute([
                            'org_id' => $org_id,
                            'fn'     => $first_name,
                            'ln'     => $last_name,
                            'un'     => $username,
                            'email'  => $email,
                            'phone'  => $phone,
                            'hash'   => $password_hash
                        ]);

                        send_email('russellhb2b@gmail.com', "New Organization Pending Approval", 
                            "New org: $name ($type) by $first_name $last_name", "Please review in admin panel.");

                        $message = "✅ Registration submitted successfully for approval. You will be notified by email.";
                    } catch (Exception $e) {
                        $error = "Database error: " . $e->getMessage();
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <img src="icons/logo-192.png" alt="Logo" class="mx-auto d-block mb-4" style="max-width:180px;">
                        <h2 class="text-center mb-4">Create Account</h2>

                        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                        <form method="post" id="registerForm">
                            <!-- Registration Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">I want to...</label>
                                <select name="registration_type" id="registrationType" class="form-select form-select-lg" required>
                                    <option value="">-- Choose --</option>
                                    <option value="new_company">Create a New Organization</option>
                                    <option value="join_company">Join an Existing Organization</option>
                                </select>
                            </div>

                            <!-- Common Fields -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <!-- New Organization Fields -->
                            <div id="newCompanyFields" style="display: none;">
                                <hr>
                                <h4>Organization Details</h4>
                                <div class="mb-3">
                                    <label>Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Physical Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Mailing / Billing Address <span class="text-danger">*</span></label>
                                    <textarea name="mailing_address" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="contact_email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Organization Type <span class="text-danger">*</span></label>
                                    <select name="organization_type" id="organizationType" class="form-select" required>
                                        <option value="retail">Retail</option>
                                        <option value="wholesale">Wholesale</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="resaleContainer" style="display: none;">
                                    <label>Resale Number <span class="text-danger" id="resaleStar">*</span></label>
                                    <input type="text" name="organization_resale_number" id="resaleNumber" class="form-control">
                                </div>
                            </div>

                            <!-- Join Existing Fields -->
                            <div id="joinCompanyFields" style="display: none;">
                                <hr>
                                <div class="mb-3">
                                    <label>Organization Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="organization_account_number" class="form-control">
                                </div>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100 mt-4">Submit Registration</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="login.php">Already have an account? Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#registrationType').on('change', function() {
            const val = $(this).val();
            $('#newCompanyFields').toggle(val === 'new_company');
            $('#joinCompanyFields').toggle(val === 'join_company');
        });

        $('#organizationType').on('change', function() {
            const isWholesale = $(this).val() === 'wholesale';
            $('#resaleContainer').toggle(isWholesale);
            $('#resaleNumber').prop('required', isWholesale);
            $('#resaleStar').toggle(isWholesale);
        });

        $('#registerForm').on('submit', function() {
            $('#submitBtn').prop('disabled', true).text('Submitting...');
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
