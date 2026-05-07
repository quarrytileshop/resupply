<?php
// organization_admin.php – Modified March 11, 2025 16:45 PDT – Lines: 352
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing organization_admin.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT organization_id, is_organization_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
if (!$user || !$user['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}
$organization_id = $user['organization_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_organization'])) {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $mailing_address = filter_input(INPUT_POST, 'mailing_address', FILTER_SANITIZE_STRING);
        $organization_type = $_POST['organization_type'] ?? 'retail';
        $organization_resale_number = filter_input(INPUT_POST, 'organization_resale_number', FILTER_SANITIZE_STRING);
        $organization_authorized_people = json_decode($_POST['organization_authorized_people'], true) ?: [];
        $is_propane = isset($_POST['is_propane']) ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE organizations SET name = :name, address = :address, mailing_address = :mailing_address, organization_type = :organization_type, organization_resale_number = :organization_resale_number, organization_authorized_people = :auth, is_propane = :is_propane WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'address' => $address,
            'mailing_address' => $mailing_address,
            'organization_type' => $organization_type,
            'organization_resale_number' => $organization_resale_number,
            'auth' => json_encode($organization_authorized_people),
            'is_propane' => $is_propane,
            'id' => $organization_id
        ]);
        // Log change
        $details = "Organization edited: " . json_encode($_POST);
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action_type, details) VALUES (:user, 'organization_edit', :details)");
        $stmt->execute(['user' => $user_id, 'details' => $details]);
        $message = "Organization details updated.";
    } elseif (isset($_POST['add_user'])) {
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $is_propane = intval($_POST['is_propane'] ?? 0);
        // Fetch organization details
        $stmt = $pdo->prepare("SELECT name, organization_account_number FROM organizations WHERE id = :id");
        $stmt->execute(['id' => $organization_id]);
        $organization = $stmt->fetch();
        // Create user
        $username = $first_name . ' ' . $last_name;
        $stmt = $pdo->prepare("INSERT INTO users (organization_id, first_name, last_name, username, email, phone_number, business_name, organization_account_number, approval_status, registration_type, is_propane) VALUES (:organization_id, :fn, :ln, :un, :email, :phone, :business, :acct, 'pending', 'invite', :propane)");
        $stmt->execute([
            'organization_id' => $organization_id,
            'fn' => $first_name,
            'ln' => $last_name,
            'un' => $username,
            'email' => $email,
            'phone' => $phone,
            'business' => $organization['name'],
            'acct' => $organization['organization_account_number'],
            'propane' => $is_propane
        ]);
        $new_user_id = $pdo->lastInsertId();
        // Generate token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires)");
        $stmt->execute(['user_id' => $new_user_id, 'token' => $token, 'expires' => $expires]);
        // Send email
        $link = "https://test.resupplyrocket.com/set_password.php?token=$token";
        $subject = "Invitation to Join Resupply Rocket";
        $html_body = '<html><body><h2>You\'ve been invited to join ' . htmlspecialchars($organization['name']) . '</h2><p>Set your password: <a href="' . $link . '">Set Password</a></p></body></html>';
        $plain_body = "You've been invited to join " . htmlspecialchars($organization['name']) . ". Set password: $link";
        send_email($email, $subject, $html_body, $plain_body);
        // Log
        $details = "User added: ID $new_user_id";
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action_type, details) VALUES (:user, 'user_added', :details)");
        $stmt->execute(['user' => $user_id, 'details' => $details]);
        $message = "User invited.";
    } elseif (isset($_POST['delete_user'])) {
        $delete_user_id = intval($_POST['delete_user_id']);
        if ($delete_user_id != $user_id) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND organization_id = :organization_id");
            $stmt->execute(['id' => $delete_user_id, 'organization_id' => $organization_id]);
            // Log
            $details = "User deleted: ID $delete_user_id";
            $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action_type, details) VALUES (:user, 'user_deleted', :details)");
            $stmt->execute(['user' => $user_id, 'details' => $details]);
            $message = "User deleted.";
        } else {
            $error = "Cannot delete yourself.";
        }
    } elseif (isset($_POST['change_cc'])) {
        $cc_name = filter_input(INPUT_POST, 'cc_name', FILTER_SANITIZE_STRING);
        $cc_street = filter_input(INPUT_POST, 'cc_street', FILTER_SANITIZE_STRING);
        $cc_city = filter_input(INPUT_POST, 'cc_city', FILTER_SANITIZE_STRING);
        $cc_state = filter_input(INPUT_POST, 'cc_state', FILTER_SANITIZE_STRING);
        $cc_zip = filter_input(INPUT_POST, 'cc_zip', FILTER_SANITIZE_STRING);
        $cc_type = filter_input(INPUT_POST, 'cc_type', FILTER_SANITIZE_STRING);
        $cc_number = filter_input(INPUT_POST, 'cc_number', FILTER_SANITIZE_STRING);
        $cc_exp = filter_input(INPUT_POST, 'cc_exp', FILTER_SANITIZE_STRING);
        $cc_billing = "$cc_street, $cc_city, $cc_state $cc_zip";
        $subject = "Credit Card Update Request for Organization ID $organization_id";
        $html_body = '<html><body><h2>Credit Card Update</h2><p>Name on Card: ' . htmlspecialchars($cc_name) . '</p><p>Billing: ' . htmlspecialchars($cc_billing) . '</p><p>Type: ' . htmlspecialchars($cc_type) . '</p><p>Number: ' . htmlspecialchars($cc_number) . '</p><p>Exp: ' . htmlspecialchars($cc_exp) . '</p></body></html>';
        $plain_body = "Credit Card Update\nName: $cc_name\nBilling: $cc_billing\nType: $cc_type\nNumber: $cc_number\nExp: $cc_exp";
        if (send_email('russellhb2b@gmail.com', $subject, $html_body, $plain_body)) {
            // Log
            $details = "Credit card change requested for organization $organization_id";
            $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action_type, details) VALUES (:user, 'cc_change_requested', :details)");
            $stmt->execute(['user' => $user_id, 'details' => $details]);
            $message = "Credit card update requested (sent to admin).";
        } else {
            $error = "Failed to send credit card update.";
        }
    }
}

// Fetch organization details
$stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = :id");
$stmt->execute(['id' => $organization_id]);
$organization = $stmt->fetch();

// Fetch organization users
$stmt = $pdo->prepare("SELECT * FROM users WHERE organization_id = :organization_id");
$stmt->execute(['organization_id' => $organization_id]);
$organization_users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Organization</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Manage Organization: <?php echo htmlspecialchars($organization['name']); ?></h1>
        <?php if (isset($message)) echo "<p class='success'>$message</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <h3>Edit Organization Details</h3>
        <form method="post">
            <input type="hidden" name="edit_organization" value="1">
            <label>Name:</label><input type="text" name="name" value="<?php echo htmlspecialchars($organization['name']); ?>" class="form-control"><br>
            <label>Physical Address:</label><textarea name="address" class="form-control"><?php echo htmlspecialchars($organization['address']); ?></textarea><br>
            <label>Mailing Address:</label><textarea name="mailing_address" class="form-control"><?php echo htmlspecialchars($organization['mailing_address']); ?></textarea><br>
            <label>Type:</label>
            <select name="organization_type" class="form-control">
                <option value="retail" <?php echo $organization['organization_type'] == 'retail' ? 'selected' : ''; ?>>Retail</option>
                <option value="wholesale" <?php echo $organization['organization_type'] == 'wholesale' ? 'selected' : ''; ?>>Wholesale</option>
            </select><br>
            <label>Resale Number:</label><input type="text" name="organization_resale_number" value="<?php echo htmlspecialchars($organization['organization_resale_number']); ?>" class="form-control"><br>
            <label>Authorized People:</label>
            <div id="auth_rows">
                <?php $auth = json_decode($organization['organization_authorized_people'] ?? '[]', true); foreach ($auth as $person): ?>
                    <div class="auth_row mb-2">
                        <input type="text" value="<?php echo htmlspecialchars($person['name']); ?>" class="form-control d-inline-block w-50">
                        <input type="email" value="<?php echo htmlspecialchars($person['email']); ?>" class="form-control d-inline-block w-50">
                        <button type="button" class="btn btn-sm btn-danger remove_row">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add_auth" class="btn btn-secondary mb-3">Add Another</button>
            <input type="hidden" name="organization_authorized_people" id="authorized_json">
            <label><input type="checkbox" name="is_propane" <?php echo $organization['is_propane'] ? 'checked' : ''; ?>> Propane Organization</label><br>
            <button type="submit" class="btn btn-primary w-100">Save Organization Details</button>
        </form>
        <h3>Add New User</h3>
        <form method="post">
            <input type="hidden" name="add_user" value="1">
            <label>First Name:</label><input type="text" name="first_name" class="form-control" required><br>
            <label>Last Name:</label><input type="text" name="last_name" class="form-control" required><br>
            <label>Email:</label><input type="email" name="email" class="form-control" required><br>
            <label>Phone:</label><input type="tel" name="phone" class="form-control"><br>
            <label><input type="checkbox" name="is_propane" value="1"> Propane-Focused</label><br>
            <button type="submit" class="btn btn-primary w-100">Invite User</button>
        </form>
        <h3>Organization Users</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organization_users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['phone_number']); ?></td>
                            <td>
                                <?php if ($u['id'] != $user_id): ?>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="delete_user" value="1">
                                        <input type="hidden" name="delete_user_id" value="<?php echo $u['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h3>Change Credit Card</h3>
        <form method="post">
            <input type="hidden" name="change_cc" value="1">
            <label>Name on Card:</label><input type="text" name="cc_name" class="form-control"><br>
            <label>Billing Street:</label><input type="text" name="cc_street" class="form-control"><br>
            <label>Billing City:</label><input type="text" name="cc_city" class="form-control"><br>
            <label>Billing State:</label><input type="text" name="cc_state" class="form-control"><br>
            <label>Billing Zip:</label><input type="text" name="cc_zip" class="form-control"><br>
            <label>Card Type:</label>
            <select name="cc_type" class="form-control">
                <option value="">Select</option>
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="American Express">American Express</option>
                <option value="Discover">Discover</option>
            </select><br>
            <label>Card Number:</label><input type="text" name="cc_number" class="form-control" pattern="\d{13,19}"><br>
            <label>Expiration (MM/YY):</label><input type="text" name="cc_exp" class="form-control" pattern="(0[1-9]|1[0-2])\/[0-9]{2}"><br>
            <button type="submit" class="btn btn-primary w-100">Submit Credit Card Update</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
    <script>
        $(document).ready(function() {
            const addAuth = document.getElementById('add_auth');
            const authRows = document.getElementById('auth_rows');
            const authorizedJson = document.getElementById('authorized_json');
            if (addAuth) addAuth.addEventListener('click', function() {
                let row = document.createElement('div');
                row.className = 'auth_row mb-2';
                row.innerHTML = '<input type="text" placeholder="Name" class="form-control d-inline-block w-50"><input type="email" placeholder="Email" class="form-control d-inline-block w-50"><button type="button" class="btn btn-sm btn-danger remove_row">Remove</button>';
                authRows.appendChild(row);
            });
            if (authRows) authRows.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove_row')) {
                    e.target.parentElement.remove();
                }
            });
            // On submit, update JSON
            document.querySelector('form').addEventListener('submit', function() {
                let auth = [];
                authRows.querySelectorAll('.auth_row').forEach(row => {
                    const name = row.querySelector('input[type="text"]').value.trim();
                    const email = row.querySelector('input[type="email"]').value.trim();
                    if (name && email) auth.push({name, email});
                });
                authorizedJson.value = JSON.stringify(auth);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
