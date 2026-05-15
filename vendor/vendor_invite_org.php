<?php
/**
 * resupply - Vendor Invite Organization Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * Fixed: Generates unique username to avoid duplicate entry error
 */

$page_title = "Invite Organization - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';
require_once '../vendor/PHPMailer/src/PHPMailer.php';
require_once '../vendor/PHPMailer/src/SMTP.php';
require_once '../vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!is_logged_in() || !is_vendor()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $org_name       = trim($_POST['org_name'] ?? '');
    $contact_email  = trim($_POST['contact_email'] ?? '');
    $contact_name   = trim($_POST['contact_name'] ?? '');

    if ($org_name && $contact_email) {
        try {
            // 1. Create organization (pending)
            $stmt = $pdo->prepare("INSERT INTO organizations 
                (name, contact_email, contact_name, vendor_id, approval_status) 
                VALUES (:name, :email, :contact, :vendor_id, 'pending')");
            $stmt->execute([
                'name'      => $org_name,
                'email'     => $contact_email,
                'contact'   => $contact_name,
                'vendor_id' => $_SESSION['vendor_id']
            ]);
            $org_id = $pdo->lastInsertId();

            // 2. Generate unique username (fixes duplicate entry error)
            $base_username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $contact_name ?: $org_name));
            $username = $base_username . rand(100, 999);

            // 3. Create user + reset token
            $reset_token   = bin2hex(random_bytes(32));
            $reset_expires = date('Y-m-d H:i:s', strtotime('+48 hours'));
            $temp_password = bin2hex(random_bytes(8));
            $password_hash = password_hash($temp_password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users 
                (organization_id, vendor_id, email, username, password_hash, first_name, approval_status, reset_token, reset_expires) 
                VALUES (:org_id, :vendor_id, :email, :username, :password_hash, :first_name, 'pending', :reset_token, :reset_expires)");
            $stmt->execute([
                'org_id'        => $org_id,
                'vendor_id'     => $_SESSION['vendor_id'],
                'email'         => $contact_email,
                'username'      => $username,
                'password_hash' => $password_hash,
                'first_name'    => $contact_name ?: 'New Contact',
                'reset_token'   => $reset_token,
                'reset_expires' => $reset_expires
            ]);

            // 4. Send invitation email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'russellhb2b@gmail.com';
            $mail->Password   = 'deykhavuhuovnpby';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('noreply@resupplyrocket.com', 'Resupply Rocket');
            $mail->addAddress($contact_email, $contact_name);
            $mail->Subject = "You're invited to join Resupply Rocket";

            $setup_link = "https://test.resupplyrocket.com/set_password.php?token=" . $reset_token;

            $mail->isHTML(true);
            $mail->Body = "
                <h2>Welcome to Resupply Rocket!</h2>
                <p>You have been invited by your vendor to join the platform.</p>
                <p><strong>Organization:</strong> " . htmlspecialchars($org_name) . "</p>
                <p>Please click the button below to set your password and activate your account:</p>
                <p><a href='$setup_link' style='background:#2c3e50;color:white;padding:12px 24px;text-decoration:none;border-radius:6px;'>Set Password & Activate Account</a></p>
                <p><small>This link will expire in 48 hours.</small></p>
            ";

            $mail->send();

            $_SESSION['message'] = "Invitation sent successfully to $contact_email!";
            header("Location: vendor_invite_org.php");
            exit;

        } catch (Exception $e) {
            $error = "Failed to send invitation. Error: " . $e->getMessage();
        }
    } else {
        $error = "Organization name and contact email are required.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Invite New Organization</h1>
    <p class="text-muted">Send an invitation so a new organization can join your resupply network.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Organization Name</label>
                    <input type="text" name="org_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Person Name</label>
                    <input type="text" name="contact_name" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Email Address</label>
                    <input type="email" name="contact_email" class="form-control" required>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Send Invitation</button>
                    <a href="vendor_organizations.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <a href="vendor_dashboard.php" class="btn btn-secondary">Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>