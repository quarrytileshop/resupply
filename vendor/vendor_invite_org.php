<?php
// vendor_invite_org.php – Generates pre-filled registration link for organization – 2026-05-12
$page_title = "Invite Organization - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin'] || !isset($_GET['org_id'])) {
    header("Location: vendor_organizations.php");
    exit;
}

$org_id = (int)$_GET['org_id'];
$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Fetch organization
$stmt = $pdo->prepare("SELECT name FROM organizations WHERE id = :id AND vendor_id = :vendor_id");
$stmt->execute(['id' => $org_id, 'vendor_id' => $vendor_id]);
$org = $stmt->fetch();

if (!$org) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Organization not found.</div></div>';
    require_once 'footer.php';
    exit;
}

// Generate pre-filled registration link
$invite_link = "https://" . $_SERVER['HTTP_HOST'] . "/register.php?vendor=" . $vendor_id . "&org=" . $org_id;
?>

<div class="container mt-4">
    <h1 class="mb-3">Invite Organization</h1>
    <p class="text-muted">Send this link to the contact at <?= htmlspecialchars($org['name']) ?> so they can register under your vendor account.</p>

    <div class="card">
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" id="inviteLink" class="form-control" value="<?= htmlspecialchars($invite_link) ?>" readonly>
                <button onclick="copyLink()" class="btn btn-primary">Copy Link</button>
            </div>
            <p class="text-muted small">This link pre-fills the organization name and vendor. The first person who registers will become the organization admin.</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="vendor_organizations.php" class="btn btn-secondary">← Back to My Customers</a>
    </div>
</div>

<script>
function copyLink() {
    const link = document.getElementById('inviteLink');
    link.select();
    document.execCommand('copy');
    alert('Invite link copied to clipboard!');
}
</script>

<?php require_once 'footer.php'; ?>
