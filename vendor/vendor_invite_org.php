<?php
/**
 * resupply - Vendor Invite Organization Page (inside vendor/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Invite Organization - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

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
        // Create a pending organization and link it to this vendor (preserves original logic)
        $stmt = $pdo->prepare("INSERT INTO organizations 
            (name, contact_email, contact_name, vendor_id, status, created_at) 
            VALUES (:name, :email, :contact, :vendor_id, 'pending', NOW())");
        
        $success = $stmt->execute([
            'name'      => $org_name,
            'email'     => $contact_email,
            'contact'   => $contact_name,
            'vendor_id' => $_SESSION['vendor_id']
        ]);

        if ($success) {
            $_SESSION['message'] = "Invitation sent to " . htmlspecialchars($org_name) . "! They can now register.";
            header("Location: vendor_invite_org.php");
            exit;
        } else {
            $error = "Failed to send invitation. Please try again.";
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
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>