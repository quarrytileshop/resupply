<?php
// register.php – Modified March 11, 2025 23:45 PDT – Lines: 248
require_once 'config.php';
require_once 'email_functions.php';
session_start();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $registration_type = $_POST['registration_type'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $is_propane = isset($_POST['is_propane']) ? 1 : 0;

    // Basic server-side validation
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
            $error = "Email already registered.";
        } else {
            $username = $first_name . ' ' . $last_name;
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            if ($registration_type === 'new_company') {
                $name = trim($_POST['name'] ?? '');
                $address = trim($_POST['address'] ?? '');
                $mailing_address = trim($_POST['mailing_address'] ?? '');
                $contact_name = trim($_POST['contact_name'] ?? '');
                $contact_email = trim($_POST['contact_email'] ?? '');
                $organization_type = $_POST['organization_type'] ?? 'retail';
                $organization_resale_number = trim($_POST['organization_resale_number'] ?? '');
                $authorized_json = $_POST['organization_authorized_people'] ?? '[]';
                $organization_authorized_people = json_decode($authorized_json, true) ?: [];

                if (empty($name) || empty($address) || empty($contact_name) || empty($contact_email)) {
                    $error = "All organization fields are required for new organization registration.";
                } else {
                    // Create pending organization
                    $stmt = $pdo->prepare("
                        INSERT INTO organizations (
                            name, address, mailing_address, contact_name, contact_email,
                            organization_type, organization_resale_number, organization_authorized_people,
                            approval_status, is_propane
                        ) VALUES (
                            :name, :address, :mailing_address, :contact_name, :contact_email,
                            :organization_type, :organization_resale_number, :auth,
                            'pending', :is_propane
                        )
                    ");
                    $stmt->execute([
                        'name' => $name,
                        'address' => $address,
                        'mailing_address' => $mailing_address,
                        'contact_name' => $contact_name,
                        'contact_email' => $contact_email,
                        'organization_type' => $organization_type,
                        'organization_resale_number' => $organization_resale_number,
                        'auth' => json_encode($organization_authorized_people),
                        'is_propane' => $is_propane
                    ]);
                    $organization_id = $pdo->lastInsertId();

                    // Create pending user linked to new organization
                    $stmt = $pdo->prepare("
                        INSERT INTO users (
                            organization_id, first_name, last_name, username, email, phone_number,
                            password_hash, approval_status, registration_type, is_propane
                        ) VALUES (
                            :organization_id, :fn, :ln, :un, :email, :phone,
                            :hash, 'pending', 'new_company', :propane
                        )
                    ");
                    $stmt->execute([
                        'organization_id' => $organization_id,
                        'fn' => $first_name,
                        'ln' => $last_name,
                        'un' => $username,
                        'email' => $email,
                        'phone' => $phone,
                        'hash' => $password_hash,
                        'propane' => $is_propane
                    ]);

                    // Send email to admin for approval
                    $subject = "New Organization Registration Pending Approval";
                    $html_body = '
                        <html><body style="font-family: Arial; color: #333;">
                            <img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;">
                            <h2>New Organization Pending Approval</h2>
                            <p><strong>Organization:</strong> ' . htmlspecialchars($name) . '</p>
                            <p><strong>Physical Address:</strong> ' . nl2br(htmlspecialchars($address)) . '</p>
                            <p><strong>Contact:</strong> ' . htmlspecialchars($contact_name) . ' (' . htmlspecialchars($contact_email) . ')</p>
                            <p><strong>Applicant:</strong> ' . htmlspecialchars($first_name . ' ' . $last_name) . ' (' . htmlspecialchars($email) . ')</p>
                            <p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>
                            <p>Review and approve in the admin panel.</p>
                        </body></html>';
                    $plain_body = strip_tags($html_body);
                    send_email('russellhb2b@gmail.com', $subject, $html_body, $plain_body);

                    $message = "Thank you! Your organization and account have been submitted for approval. You will receive an email when approved.";
                }
            } elseif ($registration_type === 'join_company') {
                $organization_account_number = trim($_POST['organization_account_number'] ?? '');
                $stmt = $pdo->prepare("SELECT id FROM organizations WHERE organization_account_number = :acct AND approval_status = 'approved'");
                $stmt->execute(['acct' => $organization_account_number]);
                $org = $stmt->fetch();
                if (!$org) {
                    $error = "Organization account number not found or not approved. Please check and try again.";
                } else {
                    $organization_id = $org['id'];
                    $stmt = $pdo->prepare("
                        INSERT INTO users (
                            organization_id, first_name, last_name, username, email, phone_number,
                            password_hash, approval_status, registration_type, is_propane
                        ) VALUES (
                            :organization_id, :fn, :ln, :un, :email, :phone,
                            :hash, 'pending', 'join_company', :propane
                        )
                    ");
                    $stmt->execute([
                        'organization_id' => $organization_id,
                        'fn' => $first_name,
                        'ln' => $last_name,
                        'un' => $username,
                        'email' => $email,
                        'phone' => $phone,
                        'hash' => $password_hash,
                        'propane' => $is_propane
                    ]);

                    // Notify organization admin(s)
                    $stmt = $pdo->prepare("SELECT email FROM users WHERE organization_id = :id AND is_organization_admin = 1");
                    $stmt->execute(['id' => $organization_id]);
                    $admins = $stmt->fetchAll();
                    foreach ($admins as $admin) {
                        send_email($admin['email'], "New User Request to Join Your Organization", 
                            "A new user has requested to join. Review in your organization admin panel.", 
                            "New user request pending approval.");
                    }

                    $message = "Your request to join the organization has been submitted. Await approval from the organization admin.";
                }
            } else {
                $error = "Please select a registration type.";
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
    <style>
        .auth_row { margin-bottom: 10px; }
        .remove_row { margin-left: 10px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <img src="icons/logo-192.png" alt="Resupply Rocket Logo" class="logo mb-4 mx-auto d-block" style="max-width: 150px;">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Register</h2>

                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post" id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">Registration Type</label>
                                <select name="registration_type" id="registrationType" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="new_company">Create New Organization</option>
                                    <option value="join_company">Join Existing Organization</option>
                                </select>
                            </div>

                            <!-- New Organization Fields -->
                            <div id="newCompanyFields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Physical Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><input type="checkbox" id="sameAddress" checked> Billing address same as physical?</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mailing Address <span class="text-danger">*</span></label>
                                    <textarea name="mailing_address" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="contact_email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="organization_type" class="form-select">
                                        <option value="retail">Retail</option>
                                        <option value="wholesale">Wholesale</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Resale Number (if wholesale)</label>
                                    <input type="text" name="organization_resale_number" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Authorized People</label>
                                    <div id="authRows"></div>
                                    <button type="button" id="addAuth" class="btn btn-secondary btn-sm mt-2">Add Person</button>
                                    <input type="hidden" name="organization_authorized_people" id="authorizedJson">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_propane" class="form-check-input" value="1" id="isPropaneNew">
                                    <label class="form-check-label" for="isPropaneNew">This is a Propane Organization (redirects users to propane order form on login)</label>
                                </div>
                            </div>

                            <!-- Join Existing Fields -->
                            <div id="joinCompanyFields" style="display:none;">
                                <div class="mb-3">
                                    <label class="form-label">Organization Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="organization_account_number" class="form-control" required>
                                </div>
                            </div>

                            <!-- Common Fields -->
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" required minlength="8">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_propane" class="form-check-input" value="1" id="isPropaneUser">
                                <label class="form-check-label" for="isPropaneUser">Propane-Focused User (redirects to propane order form on login)</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Register</button>
                        </form>

                        <div class="mt-4 text-center">
                            <a href="login.php">Already have an account? Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle registration type fields
            $('#registrationType').change(function() {
                let val = $(this).val();
                $('#newCompanyFields').toggle(val === 'new_company');
                $('#joinCompanyFields').toggle(val === 'join_company');
            });

            // Sync mailing address with physical if checked
            $('#sameAddress').change(function() {
                if (this.checked) {
                    $('textarea[name="mailing_address"]').val($('textarea[name="address"]').val()).prop('disabled', true);
                } else {
                    $('textarea[name="mailing_address"]').prop('disabled', false);
                }
            });

            $('textarea[name="address"]').on('input', function() {
                if ($('#sameAddress').is(':checked')) {
                    $('textarea[name="mailing_address"]').val(this.value);
                }
            });

            // Add authorized person row
            $('#addAuth').click(function() {
                $('#authRows').append(`
                    <div class="auth_row mb-2 input-group">
                        <input type="text" placeholder="Name" class="form-control">
                        <input type="email" placeholder="Email" class="form-control">
                        <button type="button" class="btn btn-sm btn-danger removeAuth">Remove</button>
                    </div>
                `);
            });

            // Remove authorized person row
            $(document).on('click', '.removeAuth', function() {
                $(this).closest('.auth_row').remove();
            });

            // On form submit, collect authorized people into JSON
            $('#registerForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                let auth = [];
                $('.auth_row').each(function() {
                    let name = $(this).find('input[type="text"]').val().trim();
                    let email = $(this).find('input[type="email"]').val().trim();
                    if (name && email) auth.push({name, email});
                });
                $('#authorizedJson').val(JSON.stringify(auth));

                // Submit the form via AJAX so we can see the response
                $.ajax({
                    url: 'register.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // The page will reload with $message or $error from PHP
                        location.reload(); // Simplest: reload to show PHP message
                    },
                    error: function() {
                        alert('Submission failed. Please try again.');
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
