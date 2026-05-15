<?php
require_once '../includes/config.php';

if (!is_vendor()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Vendor Dashboard';
require_once '../includes/header.php';
?>

<h1 class="mb-4">Vendor Dashboard</h1>

<div class="alert alert-info">
    Welcome! You can monitor your organizations and usage here.
</div>

<a href="<?= BASE_URL ?>vendor/vendor_organizations.php" class="btn btn-primary">View Organizations &amp; Usage</a>

<?php require_once '../includes/footer.php'; ?>