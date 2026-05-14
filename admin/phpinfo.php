<?php
/**
 * resupply - PHP Info Diagnostic Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 * Only accessible to super-admins for security
 */

$page_title = "PHP Info - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

// Security: Only show phpinfo() to super-admins
if (!is_super_admin()) {
    die("Access denied.");
}
?>

<div class="container mt-4">
    <h1 class="mb-4">PHP Information</h1>
    <p class="text-muted">Diagnostic page — visible only to Super Admins.</p>

    <div class="alert alert-warning">
        <strong>Warning:</strong> This page contains sensitive server information. 
        Do not share screenshots publicly.
    </div>

    <?php
    // Clean output buffering so phpinfo() displays correctly
    ob_clean();
    phpinfo();
    exit; // Stop further execution after phpinfo()
    ?>
</div>

<?php require_once '../includes/footer.php'; ?>