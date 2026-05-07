<?php
// invite_user.php - Modification Date: August 29, 2025, 10:00 AM - Total Lines: 150
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing invite_user.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$is_company_admin = false;
$is_site_admin = $_SESSION['is_admin'] ?? false;
// Check if user is company admin or site admin
$stmt = $pdo->prepare("SELECT company_id, is_company_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
if ($user) {
    $is_company_admin = $user['is_company_admin'];
    $company_id = $user['company_id'];
} else {
    header("Location: dashboard.php");
    exit;
}
if (!$is_company_admin && !$is_site_admin) {
    header("Location: dashboard.php");
    exit;
}
// If site admin, allow choosing company; else fixed to own company
if ($is_site_admin) {
    $selected_company_id = intval($_POST['company_id'] ?? 0);
    // Fetch companies for dropdown
    $stmt = $pdo->query("SELECT id, name FROM companies WHERE approval_status = 'approved' ORDER BY name");
    $companies = $stmt->fetchAll();
} else {
    $selected_company_id = $company_id;
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invite'])) {
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $is_propane = intval($_POST['is_propane'] ?? 0);
    // Fetch company details
    $stmt = $pdo->prepare("SELECT name, account_number FROM companies WHERE id = :id");
    $stmt->execute(['id' => $selected_company_id]);
    $company = $stmt->fetch();
    if (!$company) {
        $error = 'Company not found.';
    } else {
        // Create user with pending status
        $username = $first_name . ' ' . $last_name;
        $stmt = $pdo->prepare("INSERT INTO users (company_id, first_name, last_name, username, email, phone_number, business_name, account_number, approval_status, registration_type, is_propane) VALUES (:company_id, :fn, :ln, :un, :email, :phone, :business, :acct, 'pending', 'pre_reg', :propane)");
        $stmt->execute([
            'company_id' => $selected_company_id,
            'fn' => $first_name,
            'ln' => $last_name,
            'un' => $username,
            'email' => $email,
            'phone' => $phone,
            'business' => $company['name'],
            'acct' => $company['account_number'],
            'propane' => $is_propane
        ]);
        $new_user_id = $pdo->lastInsertId();
        // Generate password reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires)");
        $stmt->execute(['user_id' => $new_user_id, 'token' => $token, 'expires' => $expires]);
        // Send email with set password link
        $link = "https://test.resupplyrocket.com/set_password.php?token=$token";
        $subject = "Set Your Password for Resupply Rocket";
        $html_body = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>Welcome to Resupply Rocket</h2><p>Your account has been created. Please set your password by clicking the link below:</p><a href="' . $link . '">Set Password</a><p>This link expires in 1 hour.</p></div></body></html>';
        $plain_body = "Welcome to Resupply Rocket. Set your password: $link (expires in 1 hour).";
        if (send_email($email, $subject, $html_body, $plain_body)) {
            $message = "Invitation sent successfully.";
        } else {
            $error = "User created, but email failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invite User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Invite New User</h1>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <?php if ($is_site_admin): ?>
                <label>Company:</label>
                <select name="company_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo $company['id']; ?>"><?php echo htmlspecialchars($company['name']); ?></option>
                    <?php endforeach; ?>
                </select><br>
            <?php else: ?>
                <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
            <?php endif; ?>
            <label>First Name:</label><input type="text" name="first_name" class="form-control" required><br>
            <label>Last Name:</label><input type="text" name="last_name" class="form-control" required><br>
            <label>Email:</label><input type="email" name="email" class="form-control" required><br>
            <label>Phone:</label><input type="tel" name="phone" class="form-control"><br>
            <label><input type="checkbox" name="is_propane" value="1"> Propane-Focused</label><br>
            <button type="submit" name="invite" class="btn btn-primary w-100">Send Invitation</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
