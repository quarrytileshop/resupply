<?php
// edit_profile.php - Modification Date: August 21, 2025, 3:00 PM - Total Lines: 200
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing edit_profile.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';
// Fetch current details
$stmt = $pdo->prepare("SELECT email, phone_number FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$current = $stmt->fetch();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $credit_card_on_file = isset($_POST['credit_card_on_file']);
    $cc_name = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_name', FILTER_SANITIZE_STRING) : '';
    $cc_street = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_street', FILTER_SANITIZE_STRING) : '';
    $cc_city = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_city', FILTER_SANITIZE_STRING) : '';
    $cc_state = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_state', FILTER_SANITIZE_STRING) : '';
    $cc_zip = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_zip', FILTER_SANITIZE_STRING) : '';
    $cc_type = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_type', FILTER_SANITIZE_STRING) : '';
    $cc_number = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_number', FILTER_SANITIZE_STRING) : '';
    $cc_exp = $credit_card_on_file ? filter_input(INPUT_POST, 'cc_exp', FILTER_SANITIZE_STRING) : '';
    $cc_billing = "$cc_street, $cc_city, $cc_state $cc_zip";
    try {
        $stmt = $pdo->prepare("UPDATE users SET email = :email, phone_number = :phone WHERE id = :id");
        $stmt->execute(['email' => $email, 'phone' => $phone, 'id' => $user_id]);
        if ($credit_card_on_file) {
            // Email CC details to admin, do not store
            $subject = "Credit Card Update Request";
            $html_body = '<html><body><h2>Credit Card Update for User ID: ' . $user_id . '</h2><p>Name on Card: ' . htmlspecialchars($cc_name) . '</p><p>Billing: ' . htmlspecialchars($cc_billing) . '</p><p>Type: ' . htmlspecialchars($cc_type) . '</p><p>Number: ' . htmlspecialchars($cc_number) . '</p><p>Exp: ' . htmlspecialchars($cc_exp) . '</p></body></html>';
            $plain_body = "Credit Card Update for User ID: $user_id\nName: $cc_name\nBilling: $cc_billing\nType: $cc_type\nNumber: $cc_number\nExp: $cc_exp";
            send_email('russellhb2b@gmail.com', $subject, $html_body, $plain_body);
            $message .= " Credit card update requested (sent to admin).";
        }
        $message = "Profile updated." . $message;
    } catch (PDOException $e) {
        error_log("Edit profile error: " . $e->getMessage());
        $error = "Database error.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Edit Profile</h1>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Email:</label><input type="email" name="email" value="<?php echo htmlspecialchars($current['email']); ?>" class="form-control" required><br>
            <label>Phone:</label><input type="tel" name="phone" value="<?php echo htmlspecialchars($current['phone_number']); ?>" class="form-control" inputmode="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890"><br>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="credit_card_on_file" id="credit_card_on_file">
                        <label class="form-check-label" for="credit_card_on_file">Update Credit Card on File (Optional)</label>
                    </div>
                    <div id="ccFields" style="display:none;">
                        <div class="mb-3">
                            <label for="cc_name" class="form-label">Name on Card</label>
                            <input type="text" name="cc_name" id="cc_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Billing Address for Card</label>
                            <input type="text" name="cc_street" id="cc_street" class="form-control mb-1" placeholder="Street">
                            <input type="text" name="cc_city" id="cc_city" class="form-control mb-1" placeholder="City">
                            <input type="text" name="cc_state" id="cc_state" class="form-control mb-1" placeholder="State">
                            <input type="text" name="cc_zip" id="cc_zip" class="form-control" placeholder="Zip">
                        </div>
                        <div class="mb-3">
                            <label for="cc_type" class="form-label">Card Type</label>
                            <select name="cc_type" id="cc_type" class="form-control">
                                <option value="">Select</option>
                                <option value="Visa">Visa</option>
                                <option value="MasterCard">MasterCard</option>
                                <option value="American Express">American Express</option>
                                <option value="Discover">Discover</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cc_number" class="form-label">Card Number</label>
                            <input type="text" name="cc_number" id="cc_number" class="form-control" pattern="\d{13,19}">
                        </div>
                        <div class="mb-3">
                            <label for="cc_exp" class="form-label">Expiration Date (MM/YY)</label>
                            <input type="text" name="cc_exp" id="cc_exp" class="form-control" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" placeholder="MM/YY">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script>
        document.getElementById('credit_card_on_file').addEventListener('change', function() {
            document.getElementById('ccFields').style.display = this.checked ? 'block' : 'none';
        });
    </script>
</body>
</html>
